from fastapi import FastAPI, HTTPException, Request, Depends, UploadFile, File
from pydantic import BaseModel
from sentence_transformers import SentenceTransformer, util
from typing import List, Optional, Tuple
import os
import secrets
import logging
import re
import io
import threading
import numpy as np
from PIL import Image

app = FastAPI(title="Duplicate Bug Detection Multilingual API")

logger = logging.getLogger("uvicorn")

# Mengarah ke folder model multilingual yang telah kita simpan di drive
MODEL_PATH = "./model_multilingual_minilm_l12"

if os.path.exists(MODEL_PATH):
    model = SentenceTransformer(MODEL_PATH)
else:
    # Fallback online jika dijalankan di server baru
    model = SentenceTransformer('paraphrase-multilingual-MiniLM-L12-v2')

# Threshold optimal baru untuk model multilingual
OPTIMAL_THRESHOLD = 0.5097

# Optional API key protection
ML_API_KEY = os.environ.get("ML_API_KEY")
MAX_CANDIDATES = int(os.environ.get("MAX_CANDIDATES", "1000"))

# OCR configuration
OCR_MAX_BYTES = int(os.environ.get("OCR_MAX_BYTES", "5242880"))
OCR_MAX_CHARS = int(os.environ.get("OCR_MAX_CHARS", "300"))
OCR_NOISE_WORDS = os.environ.get("OCR_NOISE_WORDS", "Search,VPN,OVPN,Pinterest,Chrome,Edge")
OCR_IGNORE_TOP = float(os.environ.get("OCR_IGNORE_TOP", "0.12"))
OCR_IGNORE_BOTTOM = float(os.environ.get("OCR_IGNORE_BOTTOM", "0.08"))
OCR_MAX_DIM = 2000

if not ML_API_KEY:
    logger.warning("ML_API_KEY is not set; API-key protection is disabled")


def require_api_key(request: Request):
    if ML_API_KEY is not None:
        key = request.headers.get("X-API-Key", "")
        if not secrets.compare_digest(key, ML_API_KEY):
            raise HTTPException(status_code=401, detail="Invalid or missing API key")


# Lazy OCR engine singleton (RapidOCR, CPU)
_ocr_engine = None
_ocr_lock = threading.Lock()


def get_ocr_engine():
    global _ocr_engine
    if _ocr_engine is None:
        with _ocr_lock:
            if _ocr_engine is None:
                from rapidocr_onnxruntime import RapidOCR
                _ocr_engine = RapidOCR()
    return _ocr_engine


# Error-like keywords that get priority ordering
_ERROR_KEYWORDS = {
    "error", "server error", "failed", "exception", "timeout", "invalid",
    "denied", "gagal", "kesalahan", "tidak dapat", "404", "500", "503",
    "502", "504", "403", "401", "422", "crash", "fatal", "panic",
}

_CLOCK_RE = re.compile(r"^\d{1,2}:\d{2}$")
_DATE_DMY_RE = re.compile(r"^\d{1,2}/\d{1,2}/\d{4}$")
_DATE_ISO_RE = re.compile(r"^\d{4}-\d{2}-\d{2}$")
_BARE_NUM_RE = re.compile(r"^\d{1,2}$")


def _is_mostly_non_alnum(s: str) -> bool:
    if not s:
        return True
    alnum_count = sum(1 for c in s if c.isalnum())
    return alnum_count / len(s) < 0.5


def _is_url_or_domain(s: str) -> bool:
    # Keep standalone numeric status codes (404, 500, 503...)
    if s.isdigit():
        return False
    if re.match(r"https?://", s, re.IGNORECASE):
        return True
    if "/" in s and "." in s:
        return True
    # Path-like patterns (e.g. username/repo, /path/to/page) without spaces
    if re.match(r"^[\w.-]+/[\w.-]+", s) and " " not in s:
        return True
    return False


def clean_ocr_text(items: list, img_height: int) -> Tuple[str, int, bool]:
    """Pure function: clean OCR items into final text.

    items: list of (text, box) where box is [[x,y],[x,y],[x,y],[x,y]] corner points.
    img_height: image height in pixels (for position filter).

    Returns (text, lines, truncated).
    """
    noise_words = os.environ.get("OCR_NOISE_WORDS", "Search,VPN,OVPN,Pinterest,Chrome,Edge")
    noise_set = set(w.strip().lower() for w in noise_words.split(",") if w.strip())
    ignore_top = float(os.environ.get("OCR_IGNORE_TOP", "0.12"))
    ignore_bottom = float(os.environ.get("OCR_IGNORE_BOTTOM", "0.08"))

    # Step 1: strip whitespace, drop empty
    cleaned = []
    for text, box in items:
        t = text.strip()
        if t:
            cleaned.append((t, box))

    # Step 2: drop lines shorter than 3 chars
    cleaned = [(t, b) for t, b in cleaned if len(t) >= 3]

    # Step 3: drop lines mostly non-alphanumeric
    cleaned = [(t, b) for t, b in cleaned if not _is_mostly_non_alnum(t)]

    # Step 4: position filter (after basic cleaning, before dedup/keyword sort)
    if ignore_top > 0 or ignore_bottom > 0:
        filtered = []
        for t, b in cleaned:
            ys = [pt[1] for pt in b]
            y_min = min(ys)
            y_max = max(ys)
            y_center = (y_min + y_max) / 2.0
            y_center_frac = y_center / img_height if img_height > 0 else 0.0
            if ignore_top > 0 and y_center_frac < ignore_top:
                continue
            if ignore_bottom > 0 and y_center_frac > (1.0 - ignore_bottom):
                continue
            filtered.append((t, b))
        cleaned = filtered

    # Step 5: drop clock/date lines
    cleaned = [
        (t, b) for t, b in cleaned
        if not _CLOCK_RE.match(t)
        and not _DATE_DMY_RE.match(t)
        and not _DATE_ISO_RE.match(t)
    ]

    # Step 6: drop bare 1-2 digit numbers
    cleaned = [(t, b) for t, b in cleaned if not _BARE_NUM_RE.match(t)]

    # Step 7: drop noise words
    cleaned = [(t, b) for t, b in cleaned if not any(nw in t.lower() for nw in noise_set)]

    # Step 8: drop URL/domain lines but keep standalone HTTP status codes
    cleaned = [(t, b) for t, b in cleaned if not _is_url_or_domain(t)]

    # Step 9: deduplicate case-insensitively (order preserved)
    seen = set()
    deduped = []
    for t, b in cleaned:
        key = t.lower()
        if key not in seen:
            seen.add(key)
            deduped.append((t, b))
    cleaned = deduped

    # Step 10: keyword priority - error-like lines first
    def _is_error_line(s: str) -> bool:
        tl = s.lower()
        return any(kw in tl for kw in _ERROR_KEYWORDS)

    error_lines = [t for t, b in cleaned if _is_error_line(t)]
    other_lines = [t for t, b in cleaned if not _is_error_line(t)]
    ordered = error_lines + other_lines

    # Step 11: cap to OCR_MAX_CHARS
    max_chars = int(os.environ.get("OCR_MAX_CHARS", "300"))
    full_text = "\n".join(ordered)
    truncated = False
    if len(full_text) > max_chars:
        full_text = full_text[:max_chars]
        truncated = True

    return full_text, len(ordered), truncated


class BugPairRequest(BaseModel):
    summary_a: str
    description_a: str
    summary_b: str
    description_b: str


@app.post("/predict-duplicate")
def predict_duplicate(request: BugPairRequest, _=Depends(require_api_key)):
    try:
        # Gabungkan Summary dan Description
        text_a = (request.summary_a + " " + request.description_a).strip()
        text_b = (request.summary_b + " " + request.description_b).strip()
        
        # Hitung embeddings
        embeddings_a = model.encode(text_a, convert_to_tensor=True)
        embeddings_b = model.encode(text_b, convert_to_tensor=True)
        
        # Hitung similarity
        similarity = float(util.cos_sim(embeddings_a, embeddings_b).item())
        
        # Klasifikasikan berdasarkan threshold
        is_duplicate = similarity >= OPTIMAL_THRESHOLD
        
        return {
            "cosine_similarity": round(similarity, 4),
            "threshold_used": OPTIMAL_THRESHOLD,
            "is_duplicate": is_duplicate,
            "message": "Bug terdeteksi DUPLIKAT!" if is_duplicate else "Bug bukan duplikat."
        }
    except Exception as e:
        raise HTTPException(status_code=500, detail=str(e))


class Candidate(BaseModel):
    id: int
    summary: str
    description: str = ""


class DetectRequest(BaseModel):
    summary: str
    description: str = ""
    candidates: List[Candidate]
    top_k: int = 5
    threshold: Optional[float] = None


@app.post("/detect")
def detect(request: DetectRequest, _=Depends(require_api_key)):
    try:
        if len(request.candidates) > MAX_CANDIDATES:
            raise HTTPException(
                status_code=422,
                detail=f"Too many candidates: {len(request.candidates)} > {MAX_CANDIDATES}",
            )
        if not request.candidates:
            return {"threshold_used": OPTIMAL_THRESHOLD, "results": []}

        threshold = request.threshold if request.threshold is not None else OPTIMAL_THRESHOLD
        query = (request.summary + " " + request.description).strip()
        texts = [(c.summary + " " + c.description).strip() for c in request.candidates]

        q = model.encode(query, convert_to_tensor=True, normalize_embeddings=True)
        c = model.encode(texts, convert_to_tensor=True, normalize_embeddings=True, batch_size=32)
        scores = util.cos_sim(q, c)[0].tolist()

        ranked = sorted(
            ({"id": cand.id, "text_score": round(s, 4)} for cand, s in zip(request.candidates, scores)),
            key=lambda r: r["text_score"], reverse=True,
        )
        results = [r for r in ranked if r["text_score"] >= threshold][: request.top_k]

        return {"threshold_used": threshold, "results": results}
    except HTTPException:
        raise
    except Exception as e:
        raise HTTPException(status_code=500, detail=str(e))


@app.post("/ocr")
def ocr(request: Request, file: UploadFile = File(...), _=Depends(require_api_key)):
    # Validate content type
    content_type = (file.content_type or "").lower()
    if content_type not in ("image/png", "image/jpeg", "image/webp"):
        raise HTTPException(status_code=415, detail="Unsupported media type")

    # Read into memory only; never write to disk
    data = file.file.read()
    if len(data) > OCR_MAX_BYTES:
        raise HTTPException(status_code=413, detail="File too large")

    # Open image; downscale longest side to <= OCR_MAX_DIM
    img = Image.open(io.BytesIO(data))
    if img.mode != "RGB":
        img = img.convert("RGB")
    img_width, img_height = img.size
    if max(img_width, img_height) > OCR_MAX_DIM:
        scale = OCR_MAX_DIM / max(img_width, img_height)
        new_size = (int(img_width * scale), int(img_height * scale))
        img = img.resize(new_size, Image.LANCZOS)
        img_width, img_height = img.size

    # Run OCR on in-memory numpy array (no disk write)
    engine = get_ocr_engine()
    img_array = np.array(img)
    result, _ = engine(img_array)

    items = []
    if result:
        for box, text, score in result:
            items.append((text, box))

    text, lines, truncated = clean_ocr_text(items, img_height)

    return {"text": text, "lines": lines, "truncated": truncated}


@app.get("/health")
def health():
    return {"status": "ok"}


if __name__ == '__main__':
    import uvicorn
    uvicorn.run(app, host="0.0.0.0", port=8000)

from fastapi import FastAPI, HTTPException, Request, Depends
from pydantic import BaseModel
from sentence_transformers import SentenceTransformer, util
from typing import List, Optional
import os
import secrets
import logging

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

if not ML_API_KEY:
    logger.warning("ML_API_KEY is not set; API-key protection is disabled")


def require_api_key(request: Request):
    if ML_API_KEY is not None:
        key = request.headers.get("X-API-Key", "")
        if not secrets.compare_digest(key, ML_API_KEY):
            raise HTTPException(status_code=401, detail="Invalid or missing API key")


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


@app.get("/health")
def health():
    return {"status": "ok"}


if __name__ == '__main__':
    import uvicorn
    uvicorn.run(app, host="0.0.0.0", port=8000)

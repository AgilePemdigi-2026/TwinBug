---
title: TwinBug ML Service
emoji: 🐞
colorFrom: green
colorTo: blue
sdk: docker
app_port: 7860
pinned: false
---

# TwinBug ML Service

Stateless FastAPI service for duplicate bug report detection, used by a Laravel app. It loads the `paraphrase-multilingual-MiniLM-L12-v2` sentence-transformers model and exposes endpoints to detect duplicate bug reports.

## Endpoints

### GET /health
Public health check.

Response:
```json
{"status": "ok"}
```

### POST /detect
Used by Laravel. Given a query bug and a list of candidates, returns candidates ranked by cosine-similarity score, filtered by threshold and limited by `top_k`.

Request:
```json
{
  "summary": "login fails on mobile",
  "description": "Users cannot log in on the mobile app after the latest update.",
  "candidates": [
    {"id": 1, "summary": "login error", "description": "login screen returns error"},
    {"id": 2, "summary": "payment crash", "description": "checkout crashes on submit"}
  ],
  "top_k": 5,
  "threshold": 0.5
}
```

Response:
```json
{
  "threshold_used": 0.5,
  "results": [
    {"id": 1, "text_score": 0.82}
  ]
}
```

- `threshold` defaults to `OPTIMAL_THRESHOLD` (0.5097) when omitted.
- Results are sorted by `text_score` descending.
- Only candidates with `text_score >= threshold` are returned, up to `top_k`.

### POST /predict-duplicate
Compares two bug reports directly.

Request:
```json
{
  "summary_a": "login fails on mobile",
  "description_a": "Users cannot log in.",
  "summary_b": "login error on app",
  "description_b": "login screen returns error"
}
```

Response:
```json
{
  "cosine_similarity": 0.82,
  "threshold_used": 0.5097,
  "is_duplicate": true,
  "message": "Bug terdeteksi DUPLIKAT!"
}
```

### POST /ocr
OCR for bug screenshots. Laravel stores the returned `text` and appends it to the `description` field when calling `POST /detect`. The `/detect` and `/predict-duplicate` schemas are unchanged.

Request (multipart/form-data, field `file`):

```bash
# curl
curl -X POST http://127.0.0.1:8001/ocr \
  -H "X-API-Key: <key>" \
  -F "file=@screenshot.png"

# PowerShell
Invoke-RestMethod -Method Post -Uri http://127.0.0.1:8001/ocr `
  -Headers @{"X-API-Key" = "<key>"} `
  -Form @{ file = Get-Item "screenshot.png" }
```

Response:
```json
{
  "text": "Server Error\n500",
  "lines": 2,
  "truncated": false
}
```

- Accepts `image/png`, `image/jpeg`, `image/webp` only (otherwise 415).
- Max size from `OCR_MAX_BYTES` (otherwise 413).
- Longest side is downscaled to at most 2000 px before OCR.
- Upload is never written to disk; image contents are never logged.
- Protected by the same `X-API-Key` check as `/detect`.

## Environment variables

| Variable | Default | Description |
|---|---|---|
| `ML_API_KEY` | (unset) | If set, `POST /detect` and `POST /predict-duplicate` require header `X-API-Key` equal to this value (otherwise 401). `GET /health` stays public. If unset, no key is required and a warning is logged at startup. |
| `MAX_CANDIDATES` | `1000` | Maximum candidates allowed per `/detect` request. Exceeding returns 422. |
| `PORT` | `7860` | Port the server listens on (used by the Docker CMD). |
| `OCR_MAX_BYTES` | `5242880` | Max upload size in bytes for `POST /ocr`. Exceeding returns 413. |
| `OCR_MAX_CHARS` | `300` | Max characters of cleaned OCR text returned by `POST /ocr`. Set `truncated=true` when capped. |
| `OCR_NOISE_WORDS` | `Search,VPN,OVPN,Pinterest,Chrome,Edge` | Comma-separated noise words dropped by `POST /ocr` text cleaning. |
| `OCR_IGNORE_TOP` | `0.12` | Top fraction of image height ignored by `POST /ocr` (browser tabs/address bar). Set 0 to disable. |
| `OCR_IGNORE_BOTTOM` | `0.08` | Bottom fraction of image height ignored by `POST /ocr` (taskbar). Set 0 to disable. |

## Run locally

```bash
uvicorn app:app --host 127.0.0.1 --port 8000
```

Then open `http://127.0.0.1:8000/health`.

## Build and run with Docker

```bash
docker build -t twinbug-ml-service .
docker run -p 8001:7860 twinbug-ml-service
```

Then call `http://127.0.0.1:8001/health`.

## Deploy to Hugging Face Spaces (Docker SDK)

1. Create a new Space on Hugging Face, choose **Docker** as the SDK, set port to `7860`.
2. Upload `Dockerfile`, `app.py`, `requirements.txt`, `README.md` (and `.dockerignore`) to the Space repository.
3. In the Space settings, add the secret `ML_API_KEY` with your desired API key value (optional; leave unset for no auth).
4. Wait for the Docker build to finish.
5. Test `GET /health` on the Space URL.

## RAM expectations

The sentence-transformers model plus PyTorch CPU needs roughly **1–2 GB RAM**. The OCR engine (RapidOCR/ONNXRuntime CPU plus OpenCV headless) adds roughly **0.3–0.6 GB RAM**. The free HF Spaces CPU tier should be sufficient.

## Model folder

The local folder `model_multilingual_minilm_l12` (~470 MB) is a copy of the stock `paraphrase-multilingual-MiniLM-L12-v2` model and is excluded from the image/git. The Dockerfile downloads the stock model at build time by default.

If you have a fine-tuned model that is not available on the Hub, you can copy the folder into the image instead: uncomment the `COPY model_multilingual_minilm_l12 ./model_multilingual_minilm_l12` line in the Dockerfile and comment out the pre-download `RUN` step. A ~470 MB folder requires Git LFS or should be published as a separate model repository on the Hugging Face Hub rather than committed directly.

from fastapi.testclient import TestClient
import os
import io

os.environ.setdefault("ML_API_KEY", "test-secret-key")
os.environ.setdefault("MAX_CANDIDATES", "1000")

from app import app, clean_ocr_text  # noqa: E402
from PIL import Image, ImageDraw

client = TestClient(app)


def test_health():
    response = client.get("/health")
    assert response.status_code == 200
    assert response.json() == {"status": "ok"}


def test_detect_empty_candidates():
    headers = {"X-API-Key": "test-secret-key"}
    response = client.post(
        "/detect",
        json={
            "summary": "login fails",
            "description": "users cannot log in",
            "candidates": [],
            "top_k": 5,
        },
        headers=headers,
    )
    assert response.status_code == 200
    body = response.json()
    assert body["results"] == []
    assert "threshold_used" in body


def test_detect_sorted_and_filtered():
    headers = {"X-API-Key": "test-secret-key"}
    response = client.post(
        "/detect",
        json={
            "summary": "login fails on mobile",
            "description": "users cannot log in on mobile app",
            "candidates": [
                {"id": 1, "summary": "login error", "description": "login screen returns error"},
                {"id": 2, "summary": "payment crash", "description": "checkout crashes on submit"},
                {"id": 3, "summary": "login fails on mobile", "description": "users cannot log in on mobile app"},
            ],
            "top_k": 2,
        },
        headers=headers,
    )
    assert response.status_code == 200
    body = response.json()
    results = body["results"]
    assert len(results) == 2
    scores = [r["text_score"] for r in results]
    assert scores == sorted(scores, reverse=True)
    ids = [r["id"] for r in results]
    assert 3 in ids


def test_detect_api_key_missing():
    response = client.post(
        "/detect",
        json={
            "summary": "login fails",
            "description": "x",
            "candidates": [{"id": 1, "summary": "login error", "description": "x"}],
        },
    )
    assert response.status_code == 401


def test_detect_api_key_wrong():
    response = client.post(
        "/detect",
        json={
            "summary": "login fails",
            "description": "x",
            "candidates": [{"id": 1, "summary": "login error", "description": "x"}],
        },
        headers={"X-API-Key": "wrong-key"},
    )
    assert response.status_code == 401


def test_detect_api_key_correct():
    response = client.post(
        "/detect",
        json={
            "summary": "login fails",
            "description": "x",
            "candidates": [{"id": 1, "summary": "login error", "description": "x"}],
        },
        headers={"X-API-Key": "test-secret-key"},
    )
    assert response.status_code == 200


def test_health_public_with_key():
    response = client.get("/health")
    assert response.status_code == 200


def test_predict_duplicate_api_key():
    response = client.post(
        "/predict-duplicate",
        json={
            "summary_a": "login fails",
            "description_a": "x",
            "summary_b": "login error",
            "description_b": "x",
        },
    )
    assert response.status_code == 401


def test_exceeding_max_candidates():
    headers = {"X-API-Key": "test-secret-key"}
    candidates = [{"id": i, "summary": "bug", "description": "x"} for i in range(1001)]
    response = client.post(
        "/detect",
        json={"summary": "login fails", "description": "x", "candidates": candidates, "top_k": 5},
        headers=headers,
    )
    assert response.status_code == 422
    assert "Too many candidates" in response.json()["detail"]


# ---- clean_ocr_text unit tests ----

def _make_item(text, y_center, img_height=1080):
    box = [[100, y_center - 10], [200, y_center - 10], [200, y_center + 10], [100, y_center + 10]]
    return (text, box)


def test_clean_ocr_text_dedup():
    items = [
        _make_item("Server Error", 300),
        _make_item("server error", 400),
        _make_item("500", 500),
    ]
    text, lines, truncated = clean_ocr_text(items, 1080)
    assert lines == 2
    assert text.lower().count("server error") == 1
    assert "500" in text


def test_clean_ocr_text_noise():
    items = [
        _make_item("Server Error", 300),
        _make_item("Pinterest", 400),
        _make_item("Search", 500),
        _make_item("OVPN", 600),
    ]
    text, lines, truncated = clean_ocr_text(items, 1080)
    assert "Pinterest" not in text
    assert "Search" not in text
    assert "OVPN" not in text
    assert "Server Error" in text


def test_clean_ocr_text_clock_date():
    items = [
        _make_item("Server Error", 300),
        _make_item("08:22", 400),
        _make_item("18/11/2026", 500),
        _make_item("2026-11-18", 600),
    ]
    text, lines, truncated = clean_ocr_text(items, 1080)
    assert "08:22" not in text
    assert "18/11/2026" not in text
    assert "2026-11-18" not in text
    assert "Server Error" in text


def test_clean_ocr_text_url_removal():
    items = [
        _make_item("Server Error", 300),
        _make_item("500", 400),
        _make_item("detec-twin.caiis.ai.id/bug-reports/6", 500),
        _make_item("https://example.com/page", 600),
    ]
    text, lines, truncated = clean_ocr_text(items, 1080)
    assert "detec-twin.caiis.ai.id/bug-reports/6" not in text
    assert "https://example.com/page" not in text
    assert "Server Error" in text
    assert "500" in text


def test_clean_ocr_text_keyword_priority():
    items = [
        _make_item("random text", 300),
        _make_item("Server Error", 400),
        _make_item("500", 500),
        _make_item("other info", 600),
    ]
    text, lines, truncated = clean_ocr_text(items, 1080)
    first_line = text.split("\n")[0]
    assert first_line in ("Server Error", "500")


def test_clean_ocr_text_cap():
    old_max = os.environ.get("OCR_MAX_CHARS")
    os.environ["OCR_MAX_CHARS"] = "50"
    try:
        items = [
            _make_item("A" * 200, 100),
            _make_item("B" * 200, 200),
        ]
        text, lines, truncated = clean_ocr_text(items, 1080)
        assert truncated is True
        assert len(text) <= 50
    finally:
        if old_max is None:
            os.environ.pop("OCR_MAX_CHARS", None)
        else:
            os.environ["OCR_MAX_CHARS"] = old_max


def test_clean_ocr_text_position_filter():
    items = [
        _make_item("Browser Tab", 50, 1080),
        _make_item("Server Error", 500, 1080),
        _make_item("Taskbar", 1050, 1080),
    ]
    text, lines, truncated = clean_ocr_text(items, 1080)
    assert "Browser Tab" not in text
    assert "Taskbar" not in text
    assert "Server Error" in text


def test_clean_ocr_text_fixture():
    fixture_text = ": Proyek deteksi duplikat TwinBug -X | Melihat Tampilan Laravel | (20) Pinterest | isajahwa/TwinBug-ml | Server Error | OVPN | detec-twin.caiis.ai.id/bug-reports/6 | 500 | Server Error | 08:22 | Search | 18/11/2026"
    parts = [p.strip() for p in fixture_text.split("|")]
    items = [_make_item(p, 300 + i * 40) for i, p in enumerate(parts)]
    text, lines, truncated = clean_ocr_text(items, 1080)
    first_lines = text.split("\n")[:2]
    assert "Server Error" in first_lines
    assert "500" in first_lines
    assert "08:22" not in text
    assert "18/11/2026" not in text
    assert "Pinterest" not in text
    assert "Search" not in text
    assert "detec-twin.caiis.ai.id/bug-reports/6" not in text
    assert "isajahwa/TwinBug-ml" not in text


# ---- /ocr endpoint tests ----

def test_ocr_api_key_missing():
    response = client.post(
        "/ocr",
        files={"file": ("test.png", io.BytesIO(b"fake"), "image/png")},
    )
    assert response.status_code == 401


def test_ocr_non_image():
    headers = {"X-API-Key": "test-secret-key"}
    response = client.post(
        "/ocr",
        files={"file": ("test.txt", io.BytesIO(b"not an image"), "text/plain")},
        headers=headers,
    )
    assert response.status_code == 415


def test_ocr_oversized():
    headers = {"X-API-Key": "test-secret-key"}
    large_data = b"x" * (6 * 1024 * 1024)
    response = client.post(
        "/ocr",
        files={"file": ("test.png", io.BytesIO(large_data), "image/png")},
        headers=headers,
    )
    assert response.status_code == 413


def test_ocr_png_returns_text():
    headers = {"X-API-Key": "test-secret-key"}
    img = Image.new("RGB", (400, 100), color="white")
    draw = ImageDraw.Draw(img)
    draw.text((10, 10), "Server Error 500", fill="black")
    buf = io.BytesIO()
    img.save(buf, format="PNG")
    buf.seek(0)
    response = client.post(
        "/ocr",
        files={"file": ("test.png", buf, "image/png")},
        headers=headers,
    )
    assert response.status_code == 200
    body = response.json()
    assert "text" in body
    assert "lines" in body
    assert "truncated" in body
    assert len(body["text"]) > 0

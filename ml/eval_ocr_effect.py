"""Standalone evaluation: compare duplicate detection with and without OCR text.

Reads a CSV with columns: summary_a, description_a, ocr_a, summary_b, description_b, ocr_b, label
Computes cosine similarity (same model as app.py) for two text variants:
  A: (summary + description)
  B: (summary + description + ocr)
Reports per variant: best threshold by F1, precision, recall, F1, score means per label.
Not imported by app.py. Listed in .gitignore.
"""

import csv
import os
import sys

import numpy as np
from sentence_transformers import SentenceTransformer
from sklearn.metrics import precision_recall_fscore_support


MODEL_PATH = "./model_multilingual_minilm_l12"
if os.path.exists(MODEL_PATH):
    model = SentenceTransformer(MODEL_PATH)
else:
    model = SentenceTransformer("paraphrase-multilingual-MiniLM-L12-v2")


def read_rows(path):
    rows = []
    with open(path, newline="", encoding="utf-8") as f:
        reader = csv.DictReader(f)
        for row in reader:
            rows.append(row)
    return rows


def build_texts(rows, use_ocr):
    texts_a, texts_b, labels = [], [], []
    for row in rows:
        a = (row.get("summary_a", "") or "").strip() + " " + (row.get("description_a", "") or "").strip()
        b = (row.get("summary_b", "") or "").strip() + " " + (row.get("description_b", "") or "").strip()
        if use_ocr:
            oa = (row.get("ocr_a", "") or "").strip()
            ob = (row.get("ocr_b", "") or "").strip()
            if oa:
                a = (a + " " + oa).strip()
            if ob:
                b = (b + " " + ob).strip()
        texts_a.append(a.strip())
        texts_b.append(b.strip())
        labels.append(int(row["label"]))
    return texts_a, texts_b, labels


def cosine_sim_matrix(emb_a, emb_b):
    emb_a = emb_a / np.linalg.norm(emb_a, axis=1, keepdims=True)
    emb_b = emb_b / np.linalg.norm(emb_b, axis=1, keepdims=True)
    return (emb_a @ emb_b.T).diagonal()


def best_threshold_by_f1(scores, labels):
    best = {"threshold": 0.5, "f1": -1.0, "precision": 0.0, "recall": 0.0}
    candidates = sorted(set(scores))
    for thr in candidates:
        preds = [1 if s >= thr else 0 for s in scores]
        p, r, f1, _ = precision_recall_fscore_support(labels, preds, average="binary", zero_division=0)
        if f1 > best["f1"]:
            best = {"threshold": thr, "f1": f1, "precision": p, "recall": r}
    return best


def evaluate(csv_path, use_ocr):
    rows = read_rows(csv_path)
    texts_a, texts_b, labels = build_texts(rows, use_ocr)
    emb_a = model.encode(texts_a, convert_to_tensor=True, normalize_embeddings=True)
    emb_b = model.encode(texts_b, convert_to_tensor=True, normalize_embeddings=True)
    scores = cosine_sim_matrix(emb_a.cpu().numpy(), emb_b.cpu().numpy())
    labels_arr = np.array(labels)

    best = best_threshold_by_f1(scores, labels)
    preds = [1 if s >= best["threshold"] else 0 for s in scores]
    p, r, f1, _ = precision_recall_fscore_support(labels, preds, average="binary", zero_division=0)

    mean_pos = float(np.mean(scores[labels_arr == 1])) if np.any(labels_arr == 1) else 0.0
    mean_neg = float(np.mean(scores[labels_arr == 0])) if np.any(labels_arr == 0) else 0.0

    return {
        "use_ocr": use_ocr,
        "threshold": round(best["threshold"], 4),
        "precision": round(p, 4),
        "recall": round(r, 4),
        "f1": round(f1, 4),
        "mean_label1": round(mean_pos, 4),
        "mean_label0": round(mean_neg, 4),
        "n": len(rows),
    }


def print_table(res_no_ocr, res_ocr):
    print("Duplicate-detection quality: with vs without OCR text")
    print("=" * 78)
    header = (
        f"{'variant':<12} {'n':>4} {'thr':>7} {'prec':>7} {'rec':>7} {'f1':>7} "
        f"{'mean label=1':>14} {'mean label=0':>14}"
    )
    print(header)
    print("-" * 78)
    for r in (res_no_ocr, res_ocr):
        variant = "no-ocr" if not r["use_ocr"] else "with-ocr"
        print(
            f"{variant:<12} {r['n']:>4} {r['threshold']:>7} {r['precision']:>7} "
            f"{r['recall']:>7} {r['f1']:>7} {r['mean_label1']:>14} {r['mean_label0']:>14}"
        )
    print("=" * 78)
    delta = res_ocr["f1"] - res_no_ocr["f1"]
    print(f"F1 delta (with-ocr - no-ocr): {delta:+.4f}")


def main():
    if len(sys.argv) < 2:
        print("Usage: python eval_ocr_effect.py <path_to_csv>")
        sys.exit(1)
    csv_path = sys.argv[1]
    res_no_ocr = evaluate(csv_path, use_ocr=False)
    res_ocr = evaluate(csv_path, use_ocr=True)
    print_table(res_no_ocr, res_ocr)


if __name__ == "__main__":
    main()

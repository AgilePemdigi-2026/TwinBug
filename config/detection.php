<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Duplicate Detection Badge Cut-offs
    |--------------------------------------------------------------------------
    |
    | These thresholds determine the similarity badge level shown for a
    | candidate detection result. Scores range from 0 to 1.
    |
    | - Low:  detection_threshold .. medium boundary
    | - Medium: medium boundary .. high boundary
    | - High: high boundary .. 1
    |
    */

    'badge_low' => [
        'min' => null, // resolved at runtime to detection_threshold
        'max' => (float) env('DETECTION_BADGE_MEDIUM_MIN', 0.65),
    ],

    'badge_medium' => [
        'min' => (float) env('DETECTION_BADGE_MEDIUM_MIN', 0.65),
        'max' => (float) env('DETECTION_BADGE_HIGH_MIN', 0.80),
    ],

    'badge_high' => [
        'min' => (float) env('DETECTION_BADGE_HIGH_MIN', 0.80),
        'max' => 1.0,
    ],

    /*
    |--------------------------------------------------------------------------
    | Image Similarity (dHash) Detection
    |--------------------------------------------------------------------------
    |
    | These thresholds govern perceptual-hash (dHash) based duplicate
    | detection for screenshots. Scores range from 0 to 1.
    |
    | - image_match_threshold: minimum image_score (1 - hamming/64) to
    |   include a candidate purely on image similarity.
    | - image_match_max_candidates: cap on image-only candidates fetched
    |   from the same application before Hamming comparison.
    | - uniformity_variance_threshold: pixel variance below this value
    |   means the image is nearly uniform and yields no hash.
    */

    'image_match_threshold' => (float) env('DETECTION_IMAGE_MATCH_THRESHOLD', 0.90),
    'image_match_max_candidates' => (int) env('DETECTION_IMAGE_MAX_CANDIDATES', 500),
    'uniformity_variance_threshold' => (float) env('DETECTION_UNIFORMITY_VARIANCE_THRESHOLD', 10.0),

];

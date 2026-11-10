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

];

<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class ImageHasher
{
    /**
     * Compute a 64-bit perceptual hash (dHash) for the given screenshot.
     *
     * The image is converted to grayscale, resized to 9x8 pixels, and each
     * pixel is compared to its right neighbour. The resulting 64 bits are
     * packed into a 16-character hex string.
     *
     * @param  string  $storedPath  Path relative to the public storage disk
     * @return string|null  16-char hex hash, or null if the image is unreadable
     *                      or nearly uniform (no useful signal)
     */
    public function hash(string $storedPath): ?string
    {
        $disk = Storage::disk('public');

        if (! $disk->exists($storedPath)) {
            return null;
        }

        $contents = $disk->get($storedPath);
        if ($contents === false) {
            return null;
        }

        $image = imagecreatefromstring($contents);
        if ($image === false) {
            return null;
        }

        try {
            // Convert to grayscale.
            imagefilter($image, IMG_FILTER_GRAYSCALE);

            // Resize to 9 columns x 8 rows (72 pixels, 64 comparisons).
            $resized = imagescale($image, 9, 8);
            if ($resized === false) {
                return null;
            }

            imagedestroy($image);
            $image = $resized;

            $width = imagesx($image);  // 9
            $height = imagesy($image); // 8

            // Guard: reject nearly uniform images (very low pixel variance).
            if (! $this->hasSufficientVariance($image)) {
                return null;
            }

            // Compare each pixel to its right neighbour: 8 cols x 8 rows = 64 bits.
            $bits = 0;
            for ($y = 0; $y < $height; $y++) {
                for ($x = 0; $x < $width - 1; $x++) {
                    $left = imagecolorat($image, $x, $y);
                    $right = imagecolorat($image, $x + 1, $y);

                    $bits = ($bits << 1) | ($left > $right ? 1 : 0);
                }
            }

            return sprintf('%016x', $bits);
        } finally {
            imagedestroy($image);
        }
    }

    /**
     * Compute Hamming distance between two 16-char hex hashes.
     * Uses per-nibble popcount to avoid signed 64-bit integer issues.
     *
     * @param  string  $hashA  16-char hex string
     * @param  string  $hashB  16-char hex string
     * @return int  Number of differing bits (0-64)
     */
    public function hammingDistance(string $hashA, string $hashB): int
    {
        if (strlen($hashA) !== 16 || strlen($hashB) !== 16) {
            return 64;
        }

        $distance = 0;
        for ($i = 0; $i < 16; $i++) {
            $nibbleA = hexdec($hashA[$i]);
            $nibbleB = hexdec($hashB[$i]);
            $distance += $this->popcount4($nibbleA ^ $nibbleB);
        }

        return $distance;
    }

    /**
     * Count set bits in a 4-bit value (0-15).
     */
    private function popcount4(int $n): int
    {
        $n = ($n & 0x5) + (($n >> 1) & 0x5);
        $n = ($n & 0x3) + (($n >> 2) & 0x3);

        return $n;
    }

    /**
     * Determine whether the image has sufficient pixel variance to be useful.
     * Returns false for nearly uniform images.
     */
    private function hasSufficientVariance($image): bool
    {
        $width = imagesx($image);
        $height = imagesy($image);
        $total = $width * $height;

        $sum = 0;
        $sumSq = 0;

        for ($y = 0; $y < $height; $y++) {
            for ($x = 0; $x < $width; $x++) {
                $color = imagecolorat($image, $x, $y);
                $sum += $color;
                $sumSq += $color * $color;
            }
        }

        $mean = $sum / $total;
        $variance = ($sumSq / $total) - ($mean * $mean);

        // Threshold from config would be ideal, but this service is stateless.
        // Use a conservative default: variance below ~10 is nearly uniform.
        return $variance > 10.0;
    }
}

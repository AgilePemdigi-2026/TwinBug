<?php

namespace Tests\Feature;

use App\Services\ImageHasher;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ImageHasherTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('public');
        // Ensure the screenshots directory exists on the public disk
        Storage::disk('public')->makeDirectory('screenshots');
    }

    private function createPngImage(string $path, int $width, int $height, callable $draw): void
    {
        $image = imagecreatetruecolor($width, $height);
        $draw($image);
        imagepng($image, Storage::disk('public')->path($path));
        imagedestroy($image);
    }

    public function test_hash_returns_16_char_hex_string(): void
    {
        $hasher = new ImageHasher();

        $path = 'screenshots/test.png';
        $this->createPngImage($path, 100, 100, function ($img) {
            $white = imagecolorallocate($img, 255, 255, 255);
            $black = imagecolorallocate($img, 0, 0, 0);
            imagefilledrectangle($img, 0, 0, 99, 99, $white);
            imagerectangle($img, 10, 10, 89, 89, $black);
        });

        $hash = $hasher->hash($path);

        $this->assertIsString($hash);
        $this->assertSame(16, strlen($hash));
        $this->assertMatchesRegularExpression('/^[0-9a-f]{16}$/', $hash);
    }

    public function test_identical_images_produce_same_hash(): void
    {
        $hasher = new ImageHasher();

        $path1 = 'screenshots/img1.png';
        $path2 = 'screenshots/img2.png';

        $draw = function ($img) {
            $white = imagecolorallocate($img, 255, 255, 255);
            $black = imagecolorallocate($img, 0, 0, 0);
            imagefilledrectangle($img, 0, 0, 99, 99, $white);
            for ($i = 0; $i < 50; $i++) {
                imagesetpixel($img, mt_rand(0, 99), mt_rand(0, 99), $black);
            }
        };

        $this->createPngImage($path1, 100, 100, $draw);
        $this->createPngImage($path2, 100, 100, $draw);

        // Note: random pixels will differ, so let's use deterministic drawing
        // Instead, let's test with the same deterministic image
        $this->createPngImage($path1, 100, 100, function ($img) {
            $white = imagecolorallocate($img, 255, 255, 255);
            $black = imagecolorallocate($img, 0, 0, 0);
            imagefilledrectangle($img, 0, 0, 99, 99, $white);
            imageline($img, 10, 10, 90, 90, $black);
            imageline($img, 10, 90, 90, 10, $black);
        });
        $this->createPngImage($path2, 100, 100, function ($img) {
            $white = imagecolorallocate($img, 255, 255, 255);
            $black = imagecolorallocate($img, 0, 0, 0);
            imagefilledrectangle($img, 0, 0, 99, 99, $white);
            imageline($img, 10, 10, 90, 90, $black);
            imageline($img, 10, 90, 90, 10, $black);
        });

        $hash1 = $hasher->hash($path1);
        $hash2 = $hasher->hash($path2);

        $this->assertSame($hash1, $hash2);
    }

    public function test_different_images_produce_different_hashes(): void
    {
        $hasher = new ImageHasher();

        $path1 = 'screenshots/diff1.png';
        $path2 = 'screenshots/diff2.png';

        $this->createPngImage($path1, 100, 100, function ($img) {
            $white = imagecolorallocate($img, 255, 255, 255);
            $black = imagecolorallocate($img, 0, 0, 0);
            imagefilledrectangle($img, 0, 0, 99, 99, $white);
            imagefilledrectangle($img, 10, 10, 50, 50, $black);
        });
        $this->createPngImage($path2, 100, 100, function ($img) {
            $white = imagecolorallocate($img, 255, 255, 255);
            $black = imagecolorallocate($img, 0, 0, 0);
            imagefilledrectangle($img, 0, 0, 99, 99, $white);
            imagefilledrectangle($img, 50, 50, 90, 90, $black);
        });

        $hash1 = $hasher->hash($path1);
        $hash2 = $hasher->hash($path2);

        $this->assertNotSame($hash1, $hash2);
    }

    public function test_uniform_image_returns_null(): void
    {
        $hasher = new ImageHasher();

        $path = 'screenshots/uniform.png';
        $this->createPngImage($path, 100, 100, function ($img) {
            $white = imagecolorallocate($img, 255, 255, 255);
            imagefilledrectangle($img, 0, 0, 99, 99, $white);
        });

        $hash = $hasher->hash($path);

        $this->assertNull($hash);
    }

    public function test_missing_file_returns_null(): void
    {
        $hasher = new ImageHasher();

        $hash = $hasher->hash('screenshots/nonexistent.png');

        $this->assertNull($hash);
    }

    public function test_hamming_distance_is_safe(): void
    {
        $hasher = new ImageHasher();

        // Same hash → distance 0
        $this->assertSame(0, $hasher->hammingDistance('aaaaaaaaaaaaaaaa', 'aaaaaaaaaaaaaaaa'));

        // All bits different → distance 64 (all f's vs all 0's)
        $this->assertSame(64, $hasher->hammingDistance('ffffffffffffffff', '0000000000000000'));

        // Single bit difference
        $this->assertSame(1, $hasher->hammingDistance('8000000000000000', '0000000000000000'));

        // Known distance: 0x1 vs 0x2 per nibble = 0001 vs 0010 = 2 bits per nibble × 16 nibbles = 32
        $this->assertSame(32, $hasher->hammingDistance('1111111111111111', '2222222222222222'));
    }

    public function test_hash_length_and_bit_count(): void
    {
        $hasher = new ImageHasher();

        $path = 'screenshots/bitcount.png';
        $this->createPngImage($path, 100, 100, function ($img) {
            $white = imagecolorallocate($img, 255, 255, 255);
            $black = imagecolorallocate($img, 0, 0, 0);
            imagefilledrectangle($img, 0, 0, 99, 99, $white);
            for ($x = 0; $x < 100; $x += 2) {
                imagesetpixel($img, $x, 50, $black);
            }
        });

        $hash = $hasher->hash($path);

        $this->assertIsString($hash);
        $this->assertSame(16, strlen($hash), 'Hash must be 16 characters (64 bits / 4 bits per hex char)');

        // Verify all bits are valid (0 or 1 when converted to binary)
        $binary = decbin(hexdec($hash));
        $this->assertLessThanOrEqual(64, strlen($binary), 'Hash must not exceed 64 bits');
    }
}

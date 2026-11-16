<?php

namespace App\Services;

use App\Models\BugReport;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use RuntimeException;

class ScreenshotAnalysisClient
{
    /**
     * Create a new client instance.
     */
    public function __construct(
        protected ?string $url,
        protected ?string $key,
        protected string $model = 'gpt-4o-mini',
        protected int $timeout = 60,
    ) {
        //
    }

    /**
     * Check whether the vision service is configured.
     */
    public function configured(): bool
    {
        return filled($this->url) && filled($this->key);
    }

    /**
     * Generate a textual description of the report's screenshot.
     *
     * @throws RuntimeException
     */
    public function analyze(BugReport $report): string
    {
        if (! $this->configured()) {
            throw new RuntimeException('Layanan analisis visual belum dikonfigurasi.');
        }

        if (! $report->hasScreenshot()) {
            throw new RuntimeException('Laporan tidak memiliki screenshot.');
        }

        $disk = Storage::disk('public');

        if (! $disk->exists($report->screenshot_path)) {
            throw new RuntimeException('Berkas screenshot tidak ditemukan.');
        }

        $mimeType = $disk->mimeType($report->screenshot_path) ?: 'image/png';
        $imageData = base64_encode($disk->get($report->screenshot_path));

        $response = Http::timeout($this->timeout)
            ->withToken((string) $this->key)
            ->post((string) $this->url, [
                'model' => $this->model,
                'temperature' => 0.2,
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'Anda adalah analis QA yang mendeskripsikan screenshot laporan bug secara objektif dalam Bahasa Indonesia.',
                    ],
                    [
                        'role' => 'user',
                        'content' => [
                            [
                                'type' => 'text',
                                'text' => $this->promptFor($report),
                            ],
                            [
                                'type' => 'image_url',
                                'image_url' => [
                                    'url' => "data:{$mimeType};base64,{$imageData}",
                                ],
                            ],
                        ],
                    ],
                ],
            ]);

        $response->throw();

        $description = $response->json('choices.0.message.content');

        if (! is_string($description) || trim($description) === '') {
            throw new RuntimeException('Layanan vision tidak mengembalikan deskripsi.');
        }

        return trim($description);
    }

    /**
     * Resolve a client from configuration.
     */
    public static function fromConfig(): self
    {
        $config = config('services.vision', []);

        return new self(
            url: $config['url'] ?? null,
            key: $config['key'] ?? null,
            model: $config['model'] ?? 'gpt-4o-mini',
            timeout: (int) ($config['timeout'] ?? 60),
        );
    }

    /**
     * Build the text prompt accompanying the screenshot.
     */
    private function promptFor(BugReport $report): string
    {
        $description = Str::limit((string) $report->description, 2000);

        return <<<PROMPT
        Judul laporan: {$report->title}
        Deskripsi laporan: {$description}

        Jelaskan halaman atau fitur yang terlihat, pesan error atau teks penting, elemen UI yang relevan, dan kondisi yang tampak pada screenshot. Gunakan 2-4 kalimat. Jangan berspekulasi di luar yang terlihat.
        PROMPT;
    }
}

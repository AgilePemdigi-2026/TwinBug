<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use RuntimeException;

class DuplicateDetectionClient
{
    /**
     * Create a new client instance.
     */
    public function __construct(
        protected string $url,
        protected int $timeout = 60,
        protected int $topK = 5,
        protected int $maxCandidates = 500,
        protected string $model = 'paraphrase-multilingual-MiniLM-L12-v2',
    ) {
        //
    }

    /**
     * Get the ML service configuration.
     *
     * @return array<string, mixed>
     */
    public function config(): array
    {
        return [
            'url' => $this->url,
            'timeout' => $this->timeout,
            'top_k' => $this->topK,
            'max_candidates' => $this->maxCandidates,
            'model' => $this->model,
        ];
    }

    /**
     * Check whether the ML service is healthy.
     */
    public function health(): bool
    {
        try {
            $response = Http::timeout($this->timeout)->get(rtrim($this->url, '/').'/health');

            return $response->successful() && $response->json('status') === 'ok';
        } catch (Throwable $e) {
            return false;
        }
    }

    /**
     * Run duplicate detection for a report against its candidates.
     *
     * @param  array<string, string>  $source  ['summary', 'description']
     * @param  list<array<string, mixed>>  $candidates  [['id', 'summary', 'description'], ...]
     * @return array{threshold_used: float, results: list<array{id: int, text_score: float}>}
     *
     * @throws RuntimeException
     */
    public function detect(array $source, array $candidates): array
    {
        $payload = [
            'summary' => $source['summary'] ?? '',
            'description' => $source['description'] ?? '',
            'top_k' => $this->topK,
            'candidates' => array_values(array_map(fn (array $candidate) => [
                'id' => $candidate['id'],
                'summary' => $candidate['summary'] ?? '',
                'description' => $candidate['description'] ?? '',
            ], $candidates)),
        ];

        $response = Http::timeout($this->timeout)
            ->retry(2, 500)
            ->post(rtrim($this->url, '/').'/detect', $payload);

        $response->throw();

        return $response->json();
    }

    /**
     * Resolve a client from configuration.
     */
    public static function fromConfig(): self
    {
        $config = config('services.ml', []);

        return new self(
            url: $config['url'] ?? '',
            timeout: (int) ($config['timeout'] ?? 60),
            topK: (int) ($config['top_k'] ?? 5),
            maxCandidates: (int) ($config['max_candidates'] ?? 500),
            model: $config['model'] ?? 'paraphrase-multilingual-MiniLM-L12-v2',
        );
    }
}

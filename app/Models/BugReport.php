<?php

namespace App\Models;

use App\Enums\BugResolution;
use App\Enums\BugSeverity;
use App\Enums\BugStatus;
use App\Enums\DetectionStatus;
use App\Enums\OcrStatus;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'user_id',
    'application_id',
    'duplicate_of_id',
    'title',
    'module',
    'description',
    'severity',
    'status',
    'resolution',
    'environment',
    'steps_to_reproduce',
    'expected_result',
    'actual_result',
    'url',
    'device_metadata',
    'resolved_at',
    'closed_at',
    'screenshot_path',
    'screenshot_name',
    'screenshot_size',
    'screenshot_dimensions',
    'image_description',
    'image_analyzed_at',
    'detection_status',
    'detection_error',
    'detected_at',
    'detection_threshold',
    'ocr_text',
    'ocr_status',
    'ocr_error',
])]
class BugReport extends Model
{
    use HasFactory;

    protected static function booted(): void
    {
        static::creating(function (BugReport $report): void {
            if ($report->application_id === null && $report->user_id !== null) {
                $report->application_id = User::whereKey($report->user_id)->value('application_id');
            }
        });
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'severity' => BugSeverity::class,
            'status' => BugStatus::class,
            'detection_status' => DetectionStatus::class,
            'detected_at' => 'datetime',
            'image_analyzed_at' => 'datetime',
            'resolution' => BugResolution::class,
            'device_metadata' => 'array',
            'resolved_at' => 'datetime',
            'closed_at' => 'datetime',
            'ocr_status' => OcrStatus::class,
        ];
    }

    /**
     * Get the user who filed this bug report.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the application this report belongs to.
     */
    public function application(): BelongsTo
    {
        return $this->belongsTo(Application::class);
    }

    /**
     * Get the detection results for this report.
     *
     * @return HasMany<DetectionResult, $this>
     */
    public function detectionResults(): HasMany
    {
        return $this->hasMany(DetectionResult::class)->orderBy('rank');
    }

    /**
     * Get the status change history for this report.
     *
     * @return HasMany<BugReportStatusHistory, $this>
     */
    public function statusHistories(): HasMany
    {
        return $this->hasMany(BugReportStatusHistory::class)->latest()->latest('id');
    }

    /**
     * Get the parent bug report if this report is marked as a duplicate (twin).
     */
    public function duplicateOf(): BelongsTo
    {
        return $this->belongsTo(BugReport::class, 'duplicate_of_id');
    }

    /**
     * Get duplicate (twin) reports pointing to this report.
     */
    public function duplicates(): HasMany
    {
        return $this->hasMany(BugReport::class, 'duplicate_of_id')->latest();
    }

    /**
     * Check whether this report has an attached screenshot.
     */
    public function hasScreenshot(): bool
    {
        return $this->screenshot_path !== null;
    }

    /**
     * Check whether this report has an AI-generated screenshot description.
     */
    public function hasImageDescription(): bool
    {
        return filled($this->image_description);
    }

    /**
     * Check whether this report has OCR-extracted text.
     */
    public function hasOcrText(): bool
    {
        return filled($this->ocr_text);
    }

    /**
     * Check whether OCR is still pending (not yet completed, failed, or skipped).
     */
    public function ocrIsPending(): bool
    {
        return ! in_array($this->ocr_status, [OcrStatus::Completed, OcrStatus::Failed, OcrStatus::Skipped]);
    }

    /**
     * Get the OCR status label, or '-' if not set.
     */
    public function ocrStatusLabel(): string
    {
        return $this->ocr_status ? $this->ocr_status->label() : '-';
    }

    /**
     * Check whether this report is marked as a duplicate.
     */
    public function isDuplicate(): bool
    {
        return $this->duplicate_of_id !== null;
    }

    /**
     * Find potential similar / twin bug reports in the same application.
     *
     * @return Collection<int, BugReport>
     */
    public function findSimilarReports(int $limit = 4): Collection
    {
        $keywords = static::extractKeywords($this->title);

        $candidates = static::query()
            ->where('application_id', $this->application_id)
            ->where('id', '!=', $this->id)
            ->where(function (Builder $query) use ($keywords) {
                if ($this->duplicate_of_id) {
                    $query->orWhere('id', $this->duplicate_of_id);
                }
                $query->orWhere('duplicate_of_id', $this->id);

                if (filled($this->module)) {
                    $query->orWhere('module', $this->module);
                }

                foreach ($keywords as $word) {
                    $query->orWhere('title', 'like', "%{$word}%");
                }
            })
            ->with(['user', 'duplicateOf'])
            ->limit(20)
            ->get();

        return $candidates->map(function (BugReport $candidate) use ($keywords): BugReport {
            $score = 0;
            $reasons = [];

            if ($this->duplicate_of_id === $candidate->id || $candidate->duplicate_of_id === $this->id) {
                $score += 100;
                $reasons[] = 'Duplikat Terhubung';
            }

            if (filled($this->module) && $candidate->module === $this->module) {
                $score += 35;
                $reasons[] = 'Modul Sama';
            }

            $matchedKeywords = 0;
            $candidateTitleLower = mb_strtolower($candidate->title);
            foreach ($keywords as $word) {
                if (str_contains($candidateTitleLower, mb_strtolower($word))) {
                    $matchedKeywords++;
                }
            }

            if ($matchedKeywords > 0) {
                $score += min(45, $matchedKeywords * 15);
                $reasons[] = "{$matchedKeywords} kata kunci mirip";
            }

            if ($this->severity === $candidate->severity) {
                $score += 10;
            }

            $candidate->setAttribute('similarity_score', min(100, $score));
            $candidate->setAttribute('similarity_reasons', $reasons);

            return $candidate;
        })
            ->filter(fn (BugReport $candidate) => ((int) $candidate->getAttribute('similarity_score')) >= 30)
            ->sortByDesc(fn (BugReport $candidate) => (int) $candidate->getAttribute('similarity_score'))
            ->take($limit)
            ->values();
    }

    /**
     * Extract significant search keywords from text.
     *
     * @return array<int, string>
     */
    public static function extractKeywords(string $text): array
    {
        $stopwords = [
            'dan', 'di', 'ke', 'dari', 'pada', 'yang', 'untuk', 'dengan', 'ini', 'itu',
            'atau', 'tidak', 'bisa', 'saat', 'ada', 'karena', 'oleh', 'jika', 'sudah',
            'akan', 'dalam', 'terus', 'gagal', 'error', 'bug', 'pada', 'aplikasi',
            'the', 'and', 'in', 'on', 'at', 'to', 'for', 'with', 'is', 'not', 'that', 'this',
        ];

        $clean = preg_replace('/[^\p{L}\p{N}\s]/u', ' ', mb_strtolower($text));
        $words = preg_split('/\s+/', (string) $clean, -1, PREG_SPLIT_NO_EMPTY) ?: [];

        $filtered = array_filter($words, function (string $word) use ($stopwords): bool {
            return mb_strlen($word) >= 3 && ! in_array($word, $stopwords, true);
        });

        return array_values(array_unique($filtered));
    }

    /**
     * Scope a query to apply report filters.
     *
     * @param  Builder  $query
     * @param  array<string, mixed>  $filters
     * @return Builder
     */
    public function scopeFilter($query, array $filters)
    {
        return $query
            ->when(filled($filters['q'] ?? null), function ($q) use ($filters) {
                $search = trim((string) $filters['q']);
                $q->where(function ($sub) use ($search) {
                    $sub->where('title', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%");
                });
            })
            ->when(filled($filters['severity'] ?? null) && $filters['severity'] !== 'all', function ($q) use ($filters) {
                $q->where('severity', $filters['severity']);
            })
            ->when(filled($filters['status'] ?? null) && $filters['status'] !== 'all', function ($q) use ($filters) {
                $q->where('status', $filters['status']);
            })
            ->when(filled($filters['module'] ?? null) && $filters['module'] !== 'all', function ($q) use ($filters) {
                $q->where('module', $filters['module']);
            })
            ->when(filled($filters['has_screenshot'] ?? null) && $filters['has_screenshot'] !== 'all', function ($q) use ($filters) {
                if ($filters['has_screenshot'] === 'yes') {
                    $q->whereNotNull('screenshot_path');
                } elseif ($filters['has_screenshot'] === 'no') {
                    $q->whereNull('screenshot_path');
                }
            })
            ->when(($filters['scope'] ?? null) === 'my' && auth()->check(), function ($q) {
                $q->where('user_id', auth()->id());
            });
    }
}

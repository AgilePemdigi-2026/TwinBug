<?php

namespace App\Models;

use App\Enums\BugSeverity;
use App\Enums\BugStatus;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'user_id',
    'application_id',
    'title',
    'module',
    'description',
    'severity',
    'status',
    'screenshot_path',
    'screenshot_name',
    'screenshot_size',
    'screenshot_dimensions',
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
     * Check whether this report has an attached screenshot.
     */
    public function hasScreenshot(): bool
    {
        return $this->screenshot_path !== null;
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

<?php

namespace App\Models;

use App\Enums\AnalysisStatus;
use App\Enums\BugSeverity;
use App\Enums\BugStatus;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

#[Fillable([
    'user_id',
    'title',
    'module',
    'description',
    'severity',
    'status',
    'analysis_status',
    'screenshot_path',
    'screenshot_name',
    'screenshot_size',
    'screenshot_dimensions',
])]
class BugReport extends Model
{
    use HasFactory;

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
            'analysis_status' => AnalysisStatus::class,
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
     * Get the historical reports that are similar to this report.
     *
     * @return HasMany<BugReportMatch, $this>
     */
    public function matches(): HasMany
    {
        return $this->hasMany(BugReportMatch::class);
    }

    /**
     * Get the reports that reference this report as a similar report.
     *
     * @return HasMany<BugReportMatch, $this>
     */
    public function matchedBy(): HasMany
    {
        return $this->hasMany(BugReportMatch::class, 'matched_report_id');
    }

    /**
     * Check whether this report has an attached screenshot.
     */
    public function hasScreenshot(): bool
    {
        return $this->screenshot_path !== null;
    }

    /**
     * Get the public URL for the attached screenshot.
     */
    public function screenshotUrl(): ?string
    {
        if (! $this->hasScreenshot()) {
            return null;
        }

        return Storage::disk('public')->url($this->screenshot_path);
    }
}

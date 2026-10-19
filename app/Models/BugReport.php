<?php

namespace App\Models;

use App\Enums\BugSeverity;
use App\Enums\BugStatus;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'user_id',
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
     * Check whether this report has an attached screenshot.
     */
    public function hasScreenshot(): bool
    {
        return $this->screenshot_path !== null;
    }
}

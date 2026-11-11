<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'bug_report_id',
    'candidate_report_id',
    'rank',
    'text_score',
    'model',
])]
class DetectionResult extends Model
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
            'rank' => 'integer',
            'text_score' => 'decimal:6',
            'model' => 'string',
        ];
    }

    /**
     * Get the source bug report being analyzed.
     */
    public function bugReport(): BelongsTo
    {
        return $this->belongsTo(BugReport::class);
    }

    /**
     * Get the candidate bug report compared against the source.
     */
    public function candidateReport(): BelongsTo
    {
        return $this->belongsTo(BugReport::class, 'candidate_report_id');
    }
}

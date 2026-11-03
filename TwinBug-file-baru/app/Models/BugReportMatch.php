<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'bug_report_id',
    'matched_report_id',
    'text_similarity',
    'image_similarity',
])]
class BugReportMatch extends Model
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
            'text_similarity' => 'float',
            'image_similarity' => 'float',
        ];
    }

    /**
     * Get the report that owns this similarity result.
     */
    public function bugReport(): BelongsTo
    {
        return $this->belongsTo(BugReport::class);
    }

    /**
     * Get the historical report matched against the submitted report.
     */
    public function matchedReport(): BelongsTo
    {
        return $this->belongsTo(BugReport::class, 'matched_report_id');
    }

    /**
     * Get the combined similarity score used in the result list.
     */
    public function overallSimilarity(): float
    {
        if ($this->image_similarity === null) {
            return round($this->text_similarity, 2);
        }

        return round(($this->text_similarity * 0.6) + ($this->image_similarity * 0.4), 2);
    }
}

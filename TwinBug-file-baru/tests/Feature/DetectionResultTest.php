<?php

namespace Tests\Feature;

use App\Enums\UserRole;
use App\Models\BugReport;
use App\Models\BugReportMatch;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class DetectionResultTest extends TestCase
{
    use RefreshDatabase;

    public function test_detection_result_lists_similar_historical_reports(): void
    {
        $user = User::factory()->create(['role' => UserRole::Reporter]);

        $report = BugReport::factory()->for($user)->create([
            'title' => 'Tombol submit tidak berfungsi',
        ]);

        $matchedReport = BugReport::factory()->duplicate()->create([
            'title' => 'Tombol checkout tidak merespon',
        ]);

        BugReportMatch::factory()->create([
            'bug_report_id' => $report->id,
            'matched_report_id' => $matchedReport->id,
            'text_similarity' => 92.50,
            'image_similarity' => 88.00,
        ]);

        $response = $this->actingAs($user)->get(route('report.detection', $report));

        $response->assertOk();
        $response->assertSeeText('Similar Historical Bug Reports');
        $response->assertSeeText('Tombol checkout tidak merespon');
        $response->assertSeeText('Similarity Score');
        $response->assertSeeText('Status');
    }

    public function test_detection_result_shows_message_when_no_similar_report_is_found(): void
    {
        $user = User::factory()->create(['role' => UserRole::Reporter]);

        $report = BugReport::factory()->for($user)->create();

        $response = $this->actingAs($user)->get(route('report.detection', $report));

        $response->assertOk();
        $response->assertSeeText('Tidak ditemukan laporan bug serupa.');
    }

    public function test_detection_result_shows_processing_message(): void
    {
        $user = User::factory()->create(['role' => UserRole::Reporter]);

        $report = BugReport::factory()->for($user)->processing()->create();

        $response = $this->actingAs($user)->get(route('report.detection', $report));

        $response->assertOk();
        $response->assertSeeText('Analisis kemiripan sedang diproses. Silakan cek kembali beberapa saat lagi.');
    }

    public function test_user_can_view_detailed_comparison_of_similar_report(): void
    {
        Storage::fake('public');
        Storage::disk('public')->put('screenshots/report.png', 'report-screenshot');
        Storage::disk('public')->put('screenshots/matched.png', 'matched-screenshot');

        $user = User::factory()->create(['role' => UserRole::Reporter]);

        $report = BugReport::factory()->for($user)->create([
            'title' => 'Tombol submit tidak berfungsi',
            'screenshot_path' => 'screenshots/report.png',
        ]);

        $matchedReport = BugReport::factory()->duplicate()->create([
            'title' => 'Tombol checkout tidak merespon',
            'screenshot_path' => 'screenshots/matched.png',
        ]);

        $match = BugReportMatch::factory()->create([
            'bug_report_id' => $report->id,
            'matched_report_id' => $matchedReport->id,
            'text_similarity' => 92.50,
            'image_similarity' => 88.00,
        ]);

        $response = $this->actingAs($user)->get(route('report.comparison', [
            'bugReport' => $report,
            'match' => $match,
        ]));

        $response->assertOk();
        $response->assertSeeText('Text Similarity Score');
        $response->assertSeeText('Image Similarity Score');
        $response->assertSeeText('92.5%');
        $response->assertSeeText('88.0%');
        $response->assertSeeText('Tombol submit tidak berfungsi');
        $response->assertSeeText('Tombol checkout tidak merespon');
        $response->assertSee(Storage::disk('public')->url('screenshots/report.png'), false);
        $response->assertSee(Storage::disk('public')->url('screenshots/matched.png'), false);
    }
}

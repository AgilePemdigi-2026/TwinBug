<?php

namespace Tests\Feature;

use App\Enums\BugStatus;
use App\Enums\UserRole;
use App\Models\BugReport;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReportHistoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_view_their_report_history(): void
    {
        $user = User::factory()->create(['role' => UserRole::Reporter]);

        BugReport::factory()->for($user)->create([
            'title' => 'Tombol submit tidak berfungsi',
            'status' => BugStatus::Open,
        ]);

        BugReport::factory()->create([
            'title' => 'Laporan milik pengguna lain',
        ]);

        $response = $this->actingAs($user)->get(route('report.history'));

        $response->assertOk();
        $response->assertSeeText('Tombol submit tidak berfungsi');
        $response->assertSeeText('Title');
        $response->assertSeeText('Status');
        $response->assertSeeText('Submission Date');
        $response->assertDontSeeText('Laporan milik pengguna lain');
    }

    public function test_report_history_shows_empty_state_message(): void
    {
        $user = User::factory()->create(['role' => UserRole::Reporter]);

        $response = $this->actingAs($user)->get(route('report.history'));

        $response->assertOk();
        $response->assertSeeText('Anda belum memiliki laporan bug.');
    }

    public function test_user_can_view_report_detail(): void
    {
        $user = User::factory()->create(['role' => UserRole::Reporter]);

        $report = BugReport::factory()->for($user)->create([
            'title' => 'Tombol submit tidak berfungsi',
            'description' => 'Tombol submit tidak merespon saat diklik pada halaman checkout',
            'status' => BugStatus::Open,
        ]);

        $response = $this->actingAs($user)->get(route('report.show', $report));

        $response->assertOk();
        $response->assertSeeText('Title');
        $response->assertSeeText('Description');
        $response->assertSeeText('Status');
        $response->assertSeeText('Priority');
        $response->assertSeeText('Attached Screenshot');
        $response->assertSeeText('Tombol submit tidak berfungsi');
        $response->assertSeeText('Tombol submit tidak merespon saat diklik pada halaman checkout');
    }

    public function test_user_can_filter_report_history_by_status(): void
    {
        $user = User::factory()->create(['role' => UserRole::Reporter]);

        BugReport::factory()->for($user)->duplicate()->create([
            'title' => 'Laporan duplikat checkout',
        ]);

        BugReport::factory()->for($user)->create([
            'title' => 'Laporan terbuka dashboard',
            'status' => BugStatus::Open,
        ]);

        $response = $this->actingAs($user)->get(route('report.history', [
            'status' => BugStatus::Duplicate->value,
        ]));

        $response->assertOk();
        $response->assertSeeText('Laporan duplikat checkout');
        $response->assertDontSeeText('Laporan terbuka dashboard');
    }

    public function test_user_cannot_view_another_users_report_detail(): void
    {
        $owner = User::factory()->create(['role' => UserRole::Reporter]);
        $otherUser = User::factory()->create(['role' => UserRole::Reporter]);

        $report = BugReport::factory()->for($owner)->create();

        $response = $this->actingAs($otherUser)->get(route('report.show', $report));

        $response->assertForbidden();
    }
}

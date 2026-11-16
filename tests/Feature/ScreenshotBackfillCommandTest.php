<?php

namespace Tests\Feature;

use App\Enums\BugSeverity;
use App\Models\Application;
use App\Models\BugReport;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ScreenshotBackfillCommandTest extends TestCase
{
    use RefreshDatabase;

    private const PNG = 'iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNk+A8AAQUBAScY42YAAAAASUVORK5CYII=';

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('public');

        config([
            'services.vision.url' => 'http://vision.test/v1/chat/completions',
            'services.vision.key' => 'test-key',
            'services.vision.model' => 'gpt-4o-mini',
            'services.vision.timeout' => 30,
        ]);
    }

    /**
     * @param  array<string, mixed>  $overrides
     */
    private function createBugReport(Application $application, array $overrides = []): BugReport
    {
        return BugReport::create(array_merge([
            'user_id' => User::factory()->create(['application_id' => $application->id])->id,
            'application_id' => $application->id,
            'title' => 'Judul bug',
            'description' => 'Deskripsi bug.',
            'severity' => BugSeverity::Medium->value,
        ], $overrides));
    }

    public function test_command_generates_descriptions_only_for_reports_needing_them(): void
    {
        Storage::disk('public')->put('screenshots/a.png', base64_decode(self::PNG));
        Storage::disk('public')->put('screenshots/b.png', base64_decode(self::PNG));

        Http::fake([
            'vision.test/*' => Http::response([
                'choices' => [
                    ['message' => ['content' => 'Deskripsi visual dari LLM.']],
                ],
            ], 200),
        ]);

        $application = Application::factory()->create();
        $needsDescription = $this->createBugReport($application, [
            'screenshot_path' => 'screenshots/a.png',
        ]);
        $withoutScreenshot = $this->createBugReport($application);
        $alreadyDescribed = $this->createBugReport($application, [
            'screenshot_path' => 'screenshots/b.png',
            'image_description' => 'Deskripsi lama.',
        ]);

        $exitCode = Artisan::call('bug-reports:analyze-screenshots');

        $this->assertSame(0, $exitCode, Artisan::output());
        $this->assertSame('Deskripsi visual dari LLM.', $needsDescription->fresh()->image_description);
        $this->assertNotNull($needsDescription->fresh()->image_analyzed_at);
        $this->assertNull($withoutScreenshot->fresh()->image_description);
        $this->assertSame('Deskripsi lama.', $alreadyDescribed->fresh()->image_description);
        Http::assertSentCount(1);
    }

    public function test_command_respects_limit(): void
    {
        foreach (range(1, 3) as $i) {
            Storage::disk('public')->put("screenshots/{$i}.png", base64_decode(self::PNG));
        }

        Http::fake([
            'vision.test/*' => Http::response([
                'choices' => [
                    ['message' => ['content' => 'Deskripsi visual.']],
                ],
            ], 200),
        ]);

        $application = Application::factory()->create();
        $reports = collect(range(1, 3))->map(fn (int $i) => $this->createBugReport($application, [
            'screenshot_path' => "screenshots/{$i}.png",
        ]));

        Artisan::call('bug-reports:analyze-screenshots', ['--limit' => 1]);

        $this->assertCount(1, $reports->filter(fn (BugReport $report) => $report->fresh()->image_description !== null));
        Http::assertSentCount(1);
    }
}

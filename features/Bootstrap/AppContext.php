<?php

namespace Features\Bootstrap;

use App\Enums\BugSeverity;
use App\Enums\BugStatus;
use App\Enums\UserRole;
use App\Mail\OtpVerificationCode;
use App\Models\Application;
use App\Models\BugReport;
use App\Models\User;
use Behat\Behat\Context\Context;
use Behat\Hook\AfterScenario;
use Behat\Hook\BeforeScenario;
use Behat\Step\Given;
use Behat\Step\Then;
use Behat\Step\When;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Foundation\Application as LaravelApplication;
use Illuminate\Foundation\Bootstrap\HandleExceptions;
use Illuminate\Foundation\Testing\Concerns\InteractsWithAuthentication;
use Illuminate\Foundation\Testing\Concerns\InteractsWithSession;
use Illuminate\Foundation\Testing\Concerns\MakesHttpRequests;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\ViewErrorBag;
use Illuminate\Testing\TestResponse;
use InvalidArgumentException;
use PHPUnit\Framework\Assert;
use PHPUnit\TextUI\CliArguments\Builder as CliArgumentsBuilder;
use PHPUnit\TextUI\Configuration\Registry;
use PHPUnit\TextUI\XmlConfiguration\DefaultConfiguration;

class AppContext implements Context
{
    use InteractsWithAuthentication, InteractsWithSession, MakesHttpRequests;

    /**
     * Mirrors the environment defined in phpunit.xml so scenarios never touch the local database.
     */
    private const ENVIRONMENT = [
        'APP_ENV' => 'testing',
        'APP_MAINTENANCE_DRIVER' => 'file',
        'BCRYPT_ROUNDS' => '4',
        'CACHE_STORE' => 'array',
        'DB_CONNECTION' => 'sqlite',
        'DB_DATABASE' => ':memory:',
        'DB_URL' => '',
        'MAIL_MAILER' => 'array',
        'QUEUE_CONNECTION' => 'sync',
        'SESSION_DRIVER' => 'array',
    ];

    private const FIELD_LABELS = [
        'name' => 'name',
        'email' => 'email',
        'password' => 'password',
        'confirm password' => 'password_confirmation',
        'application' => 'application_join',
        'title' => 'title',
        'description' => 'description',
        'module' => 'module',
        'severity' => 'severity',
    ];

    protected static ?LaravelApplication $application = null;

    protected $app;

    private string $currentPage = '/';

    /** @var array<string, mixed> */
    private array $form = [];

    /** @var array<string, UploadedFile> */
    private array $uploads = [];

    /** @var array<string, string> */
    private array $filters = [];

    private ?TestResponse $response = null;

    private ?TestResponse $submitResponse = null;

    private ?TestResponse $followedResponse = null;

    private bool $submitHadErrors = false;

    private ?BugReport $currentReport = null;

    private ?User $user = null;

    #[BeforeScenario]
    public function bootScenario(): void
    {
        $this->app = self::application();

        Mail::fake();
        Storage::fake('public');
        Cache::flush();
        Auth::forgetGuards();

        DB::beginTransaction();

        $this->flushHeaders();
        $this->flushSession();

        $this->currentPage = '/';
        $this->form = [];
        $this->uploads = [];
        $this->filters = [];
        $this->response = null;
        $this->submitResponse = null;
        $this->followedResponse = null;
        $this->submitHadErrors = false;
        $this->currentReport = null;
        $this->user = null;
    }

    #[AfterScenario]
    public function rollbackScenario(): void
    {
        while (DB::transactionLevel() > 0) {
            DB::rollBack();
        }
    }

    #[Given('an application named :name exists')]
    public function anApplicationNamedExists(string $name): void
    {
        Application::firstOrCreate(['name' => $name], ['code' => Application::generateCode()]);
    }

    #[Given('a registered account :email with password :password exists')]
    public function aRegisteredAccountExists(string $email, string $password): void
    {
        $application = Application::firstOrCreate(
            ['name' => 'TwinBug Demo'],
            ['code' => Application::generateCode()],
        );

        User::updateOrCreate(
            ['email' => $email],
            [
                'name' => 'Pengguna Uji',
                'role' => UserRole::Reporter,
                'application_id' => $application->id,
                'password' => Hash::make($password),
                'email_verified_at' => now(),
            ],
        );
    }

    #[Given('I am logged in as a reporter')]
    public function iAmLoggedInAsReporter(): void
    {
        $this->loginAs(UserRole::Reporter);
    }

    #[Given('I am logged in as a developer')]
    public function iAmLoggedInAsDeveloper(): void
    {
        $this->loginAs(UserRole::Developer);
    }

    #[Given('I have submitted a bug report :title')]
    public function iHaveSubmittedABugReport(string $title): void
    {
        $this->createReport($title, BugStatus::Open);
    }

    #[Given('I have submitted a bug report :title with status :status')]
    public function iHaveSubmittedABugReportWithStatus(string $title, string $status): void
    {
        $this->createReport($title, $this->statusFromLabel($status));
    }

    #[Given('I am on the :page page')]
    public function iAmOnThePage(string $page): void
    {
        $this->currentPage = $page;
        $this->response = $this->get($page);

        Assert::assertLessThan(
            400,
            $this->response->getStatusCode(),
            "Halaman [{$page}] membalas dengan status {$this->response->getStatusCode()}.",
        );
    }

    #[When('the page loads')]
    #[When('the report detail is opened')]
    public function thePageLoads(): void
    {
        Assert::assertNotNull($this->response, 'Belum ada halaman yang dibuka.');
    }

    #[When('I fill in :label with :value')]
    public function iFillIn(string $label, string $value): void
    {
        $field = self::FIELD_LABELS[strtolower($label)] ?? strtolower(str_replace(' ', '_', $label));

        $this->form[$field] = $field === 'severity'
            ? $this->severityFromLabel($value)->value
            : $value;
    }

    #[When('I select the :role role')]
    public function iSelectTheRole(string $role): void
    {
        $this->form['role'] = strtolower($role);
    }

    #[When('I accept the terms and conditions')]
    public function iAcceptTheTerms(): void
    {
        $this->form['terms'] = '1';
    }

    #[When('I attach a screenshot :file')]
    public function iAttachAScreenshot(string $file): void
    {
        $extension = strtolower(pathinfo($file, PATHINFO_EXTENSION));

        $this->uploads['screenshot'] = in_array($extension, ['png', 'jpg', 'jpeg', 'gif', 'webp'], true)
            ? UploadedFile::fake()->image($file)
            : UploadedFile::fake()->create($file, 100, 'application/pdf');
    }

    #[When('I press :button')]
    public function iPress(string $button): void
    {
        $data = array_merge($this->form, $this->uploads);

        $this->followedResponse = null;
        $this->from($this->currentPage);

        $this->submitResponse = match (true) {
            $this->currentPage === '/login' => $this->post('/login', $data),
            $this->currentPage === '/register' => $this->post('/register', $data),
            $this->currentPage === '/otp/verify' => $this->post('/otp/verify', $data),
            $this->currentPage === '/bug-reports/create' => $this->post('/bug-reports', $data),
            $this->currentPage === '/bug-reports' => $this->get('/bug-reports?'.http_build_query($this->filters)),
            default => $this->post($this->currentPage, $data),
        };

        $this->response = $this->submitResponse;
        $this->submitHadErrors = $this->hasValidationErrors();
    }

    #[When('I submit the OTP code from the email')]
    public function iSubmitTheOtpCode(): void
    {
        $mailable = Mail::sent(OtpVerificationCode::class)->last();

        Assert::assertNotNull($mailable, 'Tidak ada email OTP yang dikirim.');

        $this->followedResponse = null;
        $this->submitResponse = $this->post('/otp/verify', ['otp_code' => $mailable->otp]);
        $this->response = $this->submitResponse;
        $this->submitHadErrors = $this->hasValidationErrors();
    }

    #[When('I click on report :title')]
    public function iClickOnReport(string $title): void
    {
        $report = BugReport::where('title', $title)->first();

        Assert::assertNotNull($report, "Laporan dengan judul [{$title}] tidak ditemukan.");

        $this->currentReport = $report;
        $this->response = $this->get(route('bug-reports.show', $report));
    }

    #[When('I select filter :label with :value')]
    public function iSelectFilter(string $label, string $value): void
    {
        $this->filters[strtolower($label)] = match (strtolower($label)) {
            'status' => $this->statusFromLabel($value)->value,
            'keparahan' => $this->severityFromLabel($value)->value,
            default => $value,
        };
    }

    #[Then('I should be redirected to :path')]
    public function iShouldBeRedirectedTo(string $path): void
    {
        Assert::assertNotNull($this->submitResponse, 'Belum ada form yang dikirim.');

        $this->submitResponse->assertRedirect($path);
    }

    #[Then('I should remain on the :path page')]
    public function iShouldRemainOnThePage(string $path): void
    {
        Assert::assertNotNull($this->submitResponse, 'Belum ada form yang dikirim.');

        $this->submitResponse->assertRedirect($path);
        Assert::assertTrue($this->submitHadErrors, 'Form tidak menampilkan kesalahan validasi.');
    }

    #[Then('I should see the message :message')]
    #[Then('I should see :text')]
    public function iShouldSee(string $text): void
    {
        $this->page()->assertSeeText($text);
    }

    #[Then('I should not see :text')]
    public function iShouldNotSee(string $text): void
    {
        $this->page()->assertDontSeeText($text);
    }

    #[Then('I should see a list of my submitted bug reports')]
    public function iShouldSeeAListOfMySubmittedBugReports(): void
    {
        $titles = BugReport::where('user_id', $this->user->id)->pluck('title')->all();

        Assert::assertNotEmpty($titles, 'Pengguna belum memiliki laporan bug.');

        foreach ($titles as $title) {
            $this->page()->assertSeeText($title);
        }
    }

    #[Then('/^each report should display "([^"]+)", "([^"]+)", and "([^"]+)"$/')]
    public function eachReportShouldDisplay(string $first, string $second, string $third): void
    {
        $this->page()->assertSeeText([$first, $second, $third]);
    }

    #[Then("I should see the report's title, description, status and priority")]
    public function iShouldSeeTheReportDetails(): void
    {
        Assert::assertNotNull($this->currentReport, 'Belum ada laporan yang dibuka.');

        $this->page()->assertSeeText([
            $this->currentReport->title,
            $this->currentReport->description,
            $this->currentReport->status->label(),
            $this->currentReport->severity->label(),
        ]);
    }

    #[Then('I should see the :section section')]
    public function iShouldSeeTheSection(string $section): void
    {
        $this->page()->assertSeeText($section);
    }

    #[Then('I should see only reports with status :status')]
    public function iShouldSeeOnlyReportsWithStatus(string $status): void
    {
        $expected = $this->statusFromLabel($status);

        $visible = BugReport::where('status', $expected->value)->pluck('title')->all();
        $hidden = BugReport::where('status', '!=', $expected->value)->pluck('title')->all();

        $page = $this->page();
        $page->assertSeeText($visible);

        foreach ($hidden as $title) {
            $page->assertDontSeeText($title);
        }
    }

    private function loginAs(UserRole $role): void
    {
        $this->user = User::factory()->create(['role' => $role]);

        $this->app['auth']->forgetGuards();
        $this->actingAs($this->user);
    }

    private function createReport(string $title, BugStatus $status): void
    {
        Assert::assertNotNull($this->user, 'Langkah ini membutuhkan pengguna yang sudah masuk.');

        $this->currentReport = BugReport::create([
            'user_id' => $this->user->id,
            'title' => $title,
            'module' => 'Checkout & Pembayaran',
            'description' => 'Tombol submit tidak merespon saat diklik pada halaman checkout.',
            'severity' => BugSeverity::Medium->value,
            'status' => $status->value,
            'screenshot_path' => 'screenshots/contoh.png',
            'screenshot_name' => 'contoh.png',
            'screenshot_size' => 204800,
            'screenshot_dimensions' => '1280A-720',
        ]);
    }

    private function page(): TestResponse
    {
        Assert::assertNotNull($this->response, 'Belum ada halaman yang dibuka.');

        if (! $this->response->isRedirect()) {
            return $this->response;
        }

        if (! $this->followedResponse instanceof TestResponse) {
            $this->followedResponse = $this->get($this->response->headers->get('Location'));
        }

        return $this->followedResponse;
    }

    /**
     * Read the validation errors right after a submit, before page() follows
     * the redirect: the follow-up GET would otherwise drop the flashed errors.
     */
    private function hasValidationErrors(): bool
    {
        $errors = $this->app['session.store']->get('errors');

        return $errors instanceof ViewErrorBag ? $errors->any() : ! empty($errors);
    }

    private function severityFromLabel(string $value): BugSeverity
    {
        $needle = strtolower($value);

        foreach (BugSeverity::cases() as $case) {
            if (strtolower($case->value) === $needle || strtolower($case->label()) === $needle) {
                return $case;
            }
        }

        throw new InvalidArgumentException("Tingkat keparahan [{$value}] tidak dikenal.");
    }

    private function statusFromLabel(string $value): BugStatus
    {
        $needle = strtolower($value);

        foreach (BugStatus::cases() as $case) {
            if (strtolower($case->value) === $needle || strtolower($case->label()) === $needle) {
                return $case;
            }
        }

        throw new InvalidArgumentException("Status [{$value}] tidak dikenal.");
    }

    private static function application(): LaravelApplication
    {
        if (static::$application instanceof LaravelApplication) {
            return static::$application;
        }

        // Behat runs outside PHPUnit; without this, a failing assertion fatals
        // while PHPUnit tries to format the failure message.
        Registry::init(
            (new CliArgumentsBuilder)->fromParameters([]),
            DefaultConfiguration::create(),
        );

        $basePath = dirname(__DIR__, 2);

        $_ENV['APP_BASE_PATH'] = $basePath;
        $_SERVER['APP_BASE_PATH'] = $basePath;

        foreach (self::ENVIRONMENT as $key => $value) {
            $_ENV[$key] = $value;
            $_SERVER[$key] = $value;
        }

        $app = require $basePath.'/bootstrap/app.php';
        $app->make(Kernel::class)->bootstrap();

        // Behat owns the process error/exception handlers; keep Laravel from hijacking them.
        restore_error_handler();
        restore_exception_handler();
        HandleExceptions::forgetApp();

        Artisan::call('migrate', ['--force' => true]);

        return static::$application = $app;
    }
}

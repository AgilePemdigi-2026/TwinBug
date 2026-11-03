# Script otomatis untuk membuat GitHub Issues ke AgilePemdigi-2026/TwinBug via GitHub REST API
# Cara pakai:
#   $env:GITHUB_TOKEN = "token_github_anda"
#   .\create_github_issues.ps1

param(
    [string]$Token = $env:GITHUB_TOKEN,
    [string]$Repo = "AgilePemdigi-2026/TwinBug"
)

if (-not $Token) {
    Write-Host "Silakan masukkan GitHub Personal Access Token (PAT):" -ForegroundColor Yellow
    $Token = Read-Host -AsSecureString
    $BSTR = [System.Runtime.InteropServices.Marshal]::SecureStringToBSTR($Token)
    $Token = [System.Runtime.InteropServices.Marshal]::PtrToStringAuto($BSTR)
}

if (-not $Token) {
    Write-Error "Token GitHub diperlukan untuk membuat issue."
    exit 1
}

$headers = @{
    "Authorization" = "Bearer $Token"
    "Accept"        = "application/vnd.github+json"
    "X-GitHub-Api-Version" = "2022-11-28"
    "User-Agent"    = "TwinBug-Issue-Creator"
}

$issues = @(
    @{
        title  = "[F-04] Implementasi Fitur Buat Laporan Bug"
        body   = @"
### Penanggung Jawab: Ivan Raditya Prisetyo (187241072)

#### Deskripsi
Mengembangkan fitur pelaporan bug agar pengguna dapat menyampaikan laporan permasalahan pada sistem TwinBug.

#### User Story
- **Sebagai**: Pengguna (Pelapor Bug)
- **Saya ingin**: Membuat dan mengirim laporan bug dengan judul, deskripsi, langkah reproduksi, dan tingkat keparahan.
- **Sehingga**: Tim pengembang dapat mengetahui dan memperbaiki masalah sistem.

#### Checklist Pengerjaan
- [ ] Formulir laporan bug (Blade view)
- [ ] Validasi form (StoreBugReportRequest)
- [ ] Penyimpanan data ke database (BugReportController)
- [ ] Penyesuaian konsistensi antarmuka UI/UX
- [ ] Unit & Feature Testing (BugReportTest)

#### Catatan / Kendala
- Penyesuaian tata letak antarmuka agar konsisten dengan komponen desain sistem.
"@
        labels = @("enhancement", "feature", "F-04", "sprint-1")
    },
    @{
        title  = "[Management] Koordinasi & Monitoring Perkembangan Sprint 1"
        body   = @"
### Penanggung Jawab: Abdul Hakim Fatur Rochman (187241111)

#### Deskripsi
Mengoordinasikan pembagian tugas anggota tim dan memantau progres pengembangan fitur agar sesuai dengan perencanaan Sprint 1.

#### Checklist Pengerjaan
- [x] Mengoordinasikan pembagian tugas anggota tim untuk pengerjaan Sprint 1.
- [ ] Memantau perkembangan pengembangan fitur (F-01, F-02, F-03, F-04, F-13).
- [ ] Sinkronisasi berkala terkait kendala antar anggota.
"@
        labels = @("management", "scrum", "sprint-1")
    },
    @{
        title  = "[F-01] Implementasi Fitur Registrasi Akun Pengguna (Register)"
        body   = @"
### Penanggung Jawab: Isa Jahwa Fatimiyah (187241029)

#### Deskripsi
Membangun fitur pendaftaran akun baru bagi calon pengguna sistem TwinBug.

#### Checklist Pengerjaan
- [x] Menyiapkan pengerjaan fitur F-01 Register
- [ ] Form registrasi akun pengguna
- [ ] Validasi request pendaftaran (RegisterRequest)
- [ ] Integrasi pengiriman kode OTP verifikasi
- [ ] Testing alur registrasi (RegistrationOtpTest)
"@
        labels = @("enhancement", "feature", "auth", "F-01", "sprint-1")
    },
    @{
        title  = "[F-03] Implementasi Fitur Keluar Sistem (Logout)"
        body   = @"
### Penanggung Jawab: Isa Jahwa Fatimiyah (187241029)

#### Deskripsi
Menyediakan aksi keluar akun secara aman dari aplikasi TwinBug.

#### Checklist Pengerjaan
- [ ] Handler logout pada controller (invalidation session + token regenerate)
- [ ] Tombol logout pada navbar / dashboard pengguna
- [ ] Redirect ke landing / login page
"@
        labels = @("enhancement", "feature", "auth", "F-03", "sprint-1")
    },
    @{
        title  = "[F-02] Implementasi Fitur Autentikasi Pengguna (Login)"
        body   = @"
### Penanggung Jawab: Muhammad Fikhlas Hakim Arya Maulana (187241034)

#### Deskripsi
Mengembangkan autentikasi login pengguna terdaftar ke dalam aplikasi TwinBug.

#### Checklist Pengerjaan
- [x] Desain halaman form Login
- [x] Implementasi AuthenticatedSessionController & LoginRequest
- [x] Validasi kredensial dan penanganan pesan error
- [x] Sesi login dan pengalihan ke dashboard
"@
        labels = @("enhancement", "feature", "auth", "F-02", "sprint-1")
    },
    @{
        title  = "[F-13] Penyelesaian Fitur Pemulihan Kata Sandi (Lupa Password)"
        body   = @"
### Penanggung Jawab: Muhammad Fikhlas Hakim Arya Maulana (187241034)

#### Deskripsi
Menyelesaikan alur lupa kata sandi dan reset password melalui notifikasi email.

#### Checklist Pengerjaan
- [x] Form permintaan lupa password (Forgot Password)
- [ ] Pengiriman link/kode reset password via email (PasswordResetLinkController)
- [ ] Form input kata sandi baru (NewPasswordController)
- [ ] Update password dan pengujian fitur
"@
        labels = @("enhancement", "feature", "auth", "F-13", "sprint-1")
    },
    @{
        title  = "[Setup] Penentuan Tech Stack & Inisialisasi Proyek Laravel"
        body   = @"
### Penanggung Jawab: Muhammad Fikhlas Hakim Arya Maulana (187241034)

#### Deskripsi
Setup fondasi arsitektur dan teknologi pengembangan sistem TwinBug.

#### Checklist Pengerjaan
- [x] Menentukan teknologi yang digunakan (Laravel, Blade, Tailwind CSS)
- [x] Setup proyek menggunakan Laravel
- [x] Konfigurasi environment dan database dasar
"@
        labels = @("setup", "devops", "sprint-1")
    },
    @{
        title  = "[F-05] Fitur Upload Screenshot Bukti Bug (Upload Screenshot on Bug Report)"
        assignees = @("AbdulHakimFaturR")
        body   = @"
### Penanggung Jawab: Abdul Hakim Fatur Rochman (187241111)

#### Deskripsi
Implementasi fitur pengunggahan tangkapan layar/screenshot bukti permasalahan (F-05) pada form pelaporan bug sistem TwinBug.

#### Checklist Pengerjaan
- [x] Input field file upload screenshot pada form laporan bug (resources/views/bug-reports/create.blade.php)
- [x] Validasi file upload (PNG, JPG, GIF, WebP max 5MB di StoreBugReportRequest)
- [x] Logic penyimpanan file screenshot ke storage disk publik (BugReportController@store)
- [x] Penyimpanan metadata screenshot (path, name, size, dimensions) ke tabel bug_reports
- [ ] Tampilan preview screenshot pada detail dan daftar laporan bug
- [ ] Automated feature test untuk validasi dan alur upload screenshot
"@
        labels = @("enhancement", "feature", "F-05", "sprint-1")
    },
    @{
        title  = "[Integration] Integrasi & Upload Fitur Sistem (Register, Login, & Konsistensi UI/UX)"
        assignees = @("Ivan-Raditya")
        body   = @"
### Penanggung Jawab: Ivan Raditya Prisetyo (187241072)

#### Deskripsi
Melakukan upload dan integrasi modul fitur utama (Registrasi, Login, dan tata letak antarmuka sistem TwinBug) ke repositori serta penyesuaian konsistensi UI/UX.

#### Checklist Pengerjaan
- [x] Upload dan integrasi modul autentikasi awal (Register, Login)
- [x] Penyesuaian layout dan antarmuka komponen form sesuai acuan desain sistem
- [x] Pengecekan konsistensi styling antar halaman antarmuka
- [x] Review dan sinkronisasi branch dengan pengerjaan anggota tim
"@
        labels = @("enhancement", "feature", "integration", "sprint-1")
    },
    @{
        title  = "[CI/CD] Setup GitHub Actions Workflow untuk Automated Testing (All Workflows)"
        assignees = @("Ivan-Raditya")
        body   = @"
### Penanggung Jawab: Ivan Raditya Prisetyo (187241072)

#### Deskripsi
Mengonfigurasi workflow CI/CD otomatis menggunakan GitHub Actions pada repositori TwinBug untuk automated testing dan code quality.

#### Checklist Pengerjaan
- [x] Buat konfigurasi workflow GitHub Actions (.github/workflows/ci.yml)
- [x] Setup PHP 8.3/8.4 environment & Composer dependency cache
- [x] Konfigurasi environment database SQLite in-memory untuk testing
- [x] Menjalankan test suite otomatis (php artisan test)
- [x] Pengecekan formatting kode menggunakan Laravel Pint
"@
        labels = @("setup", "devops", "ci-cd", "sprint-1")
    }
)

$uri = "https://api.github.com/repos/$Repo/issues"

foreach ($issue in $issues) {
    Write-Host "Membuat issue: $($issue.title)..." -ForegroundColor Cyan
    $payload = $issue | ConvertTo-Json -Depth 5
    try {
        $response = Invoke-RestMethod -Uri $uri -Method Post -Headers $headers -Body ([System.Text.Encoding]::UTF8.GetBytes($payload)) -ContentType "application/json; charset=utf-8"
        Write-Host " Berhasil: $($response.html_url)" -ForegroundColor Green
    } catch {
        Write-Host " Gagal membuat issue: $($_.Exception.Message)" -ForegroundColor Red
    }
}

Write-Host "Selesai!" -ForegroundColor Green

# Sprint 1 Issues - TwinBug

Daftar issue resmi Sprint 1 pada repositori [AgilePemdigi-2026/TwinBug](https://github.com/AgilePemdigi-2026/TwinBug/issues).

---

## Ringkasan Pembagian Task

| Issue | Title | Assignee | NIM | Status |
|---|---|---|---|---|
| [#1](https://github.com/AgilePemdigi-2026/TwinBug/issues/1) | [F-04] Fitur Buat Laporan Bug (Create Bug Report) | [@Ivan-Raditya](https://github.com/Ivan-Raditya) | 187241072 | In Progress |
| [#2](https://github.com/AgilePemdigi-2026/TwinBug/issues/2) | [Sprint 1] Koordinasi Tim & Monitoring Progress Fitur | [@AbdulHakimFaturR](https://github.com/AbdulHakimFaturR) | 187241111 | Ongoing |
| [#3](https://github.com/AgilePemdigi-2026/TwinBug/issues/3) | [F-01] Fitur Registrasi Akun Pengguna & Verifikasi OTP | [@isajahwa](https://github.com/isajahwa) | 187241029 | In Progress |
| [#4](https://github.com/AgilePemdigi-2026/TwinBug/issues/4) | [F-03] Fitur Keluar Sistem (Logout) | [@isajahwa](https://github.com/isajahwa) | 187241029 | To Do |
| [#5](https://github.com/AgilePemdigi-2026/TwinBug/issues/5) | [F-02] Otentikasi Login Pengguna | [@fikhlassm](https://github.com/fikhlassm) | 187241034 | Done / In Review |
| [#6](https://github.com/AgilePemdigi-2026/TwinBug/issues/6) | [F-13] Fitur Lupa Password & Reset Password via Email | [@fikhlassm](https://github.com/fikhlassm) | 187241034 | In Progress |
| [#7](https://github.com/AgilePemdigi-2026/TwinBug/issues/7) | [Setup] Setup Tech Stack & Inisialisasi Proyek Laravel | [@fikhlassm](https://github.com/fikhlassm) | 187241034 | Done |
| [#8](https://github.com/AgilePemdigi-2026/TwinBug/issues/8) | [F-05] Fitur Upload Screenshot Bukti Bug (Upload Screenshot on Bug Report) | [@AbdulHakimFaturR](https://github.com/AbdulHakimFaturR) | 187241111 | In Progress |
| [#9](https://github.com/AgilePemdigi-2026/TwinBug/issues/9) | [Integration] Integrasi & Upload Fitur Sistem (Register, Login, & Konsistensi UI/UX) | [@Ivan-Raditya](https://github.com/Ivan-Raditya) | 187241072 | Done / In Progress |
| [#10](https://github.com/AgilePemdigi-2026/TwinBug/issues/10) | [CI/CD] Setup GitHub Actions Workflow untuk Automated Testing (All Workflows) | [@Ivan-Raditya](https://github.com/Ivan-Raditya) | 187241072 | In Progress |

---

## Detail Issue

### [#1] [F-04] Fitur Buat Laporan Bug (Create Bug Report)
* **PIC**: Ivan Raditya Prisetyo (187241072) - `@Ivan-Raditya`
* **Labels**: `enhancement`
* **Status**: In Progress
* **Deskripsi**: Pengembangan fitur pelaporan bug (`F-04`) agar user dapat menyampaikan laporan kendala atau bug pada aplikasi TwinBug secara terstruktur.
* **Scope / Checklist**:
  - [ ] Form view input laporan bug (`resources/views/bug-reports/create.blade.php`)
  - [ ] Validasi form request input bug report (`StoreBugReportRequest`)
  - [ ] Logic penyimpanan bug report ke database (`BugReportController@store`)
  - [ ] Penyesuaian layout antarmuka (UI/UX) form pelaporan agar seragam dengan desain
  - [ ] Feature test pembuatan bug report (`tests/Feature/BugReportTest.php`)
* **Catatan / Kendala**: Tata letak antarmuka (UI/UX) pada tahap awal masih belum sepenuhnya konsisten, perlu penyesuaian berkala dengan komponen form lainnya.

---

### [#2] [Sprint 1] Koordinasi Tim & Monitoring Progress Fitur
* **PIC**: Abdul Hakim Fatur Rochman (187241111) - `@AbdulHakimFaturR`
* **Status**: Ongoing
* **Deskripsi**: Mengoordinasikan alur pengerjaan Sprint 1, pembagian tugas anggota tim, serta memantau progres pengembangan fitur sistem TwinBug agar sesuai timeline.
* **Scope / Checklist**:
  - [x] Koordinasi pembagian tugas anggota tim untuk pengerjaan Sprint 1
  - [ ] Monitoring progress pengerjaan fitur (F-01, F-02, F-03, F-04, F-13)
  - [ ] Evaluasi kendala teknis anggota tim selama pengerjaan Sprint 1
  - [ ] Review kelengkapan deliverable menjelang akhir Sprint 1

---

### [#3] [F-01] Fitur Registrasi Akun Pengguna & Verifikasi OTP
* **PIC**: Isa Jahwa Fatimiyah (187241029) - `@isajahwa`
* **Labels**: `enhancement`
* **Status**: In Progress
* **Deskripsi**: Pengembangan fitur pendaftaran akun baru (`F-01`) untuk user pada sistem TwinBug beserta integrasi pengiriman kode OTP verifikasi email.
* **Scope / Checklist**:
  - [x] Persiapan struktur pengerjaan fitur F-01
  - [ ] Implementasi form registrasi akun (`resources/views/auth/register.blade.php`)
  - [ ] Validasi data pendaftaran (`RegisterRequest`)
  - [ ] Integrasi verifikasi OTP (`OtpVerificationController`)
  - [ ] Pengujian automated test registrasi & OTP (`RegistrationOtpTest`)

---

### [#4] [F-03] Fitur Keluar Sistem (Logout)
* **PIC**: Isa Jahwa Fatimiyah (187241029) - `@isajahwa`
* **Labels**: `enhancement`
* **Status**: To Do
* **Deskripsi**: Implementasi fungsionalitas logout (`F-03`) untuk mengakhiri sesi user secara aman dari sistem TwinBug.
* **Scope / Checklist**:
  - [x] Persiapan alur kerja logout sesuai pembagian tugas Sprint 1
  - [ ] Endpoint / controller handler logout (`AuthenticatedSessionController@destroy`)
  - [ ] Invalidation session & regenerate CSRF token
  - [ ] Tombol logout pada navbar / dashboard antarmuka
  - [ ] Redirect user ke halaman login setelah logout

---

### [#5] [F-02] Otentikasi Login Pengguna
* **PIC**: Muhammad Fikhlas Hakim Arya Maulana (187241034) - `@fikhlassm`
* **Labels**: `enhancement`
* **Status**: Done / In Review
* **Deskripsi**: Pengembangan fitur login user (`F-02`) untuk otentikasi akun terdaftar agar dapat masuk ke dashboard sistem TwinBug.
* **Scope / Checklist**:
  - [x] Desain dan implementasi halaman login (`resources/views/auth/login.blade.php`)
  - [x] Form request validation (`LoginRequest`)
  - [x] Logika autentikasi sesi user (`AuthenticatedSessionController@store`)
  - [x] Penanganan error kredensial salah / akun tidak ditemukan
  - [x] Redirect ke dashboard setelah login sukses

---

### [#6] [F-13] Fitur Lupa Password & Reset Password via Email
* **PIC**: Muhammad Fikhlas Hakim Arya Maulana (187241034) - `@fikhlassm`
* **Labels**: `enhancement`
* **Status**: In Progress
* **Deskripsi**: Pengembangan alur pemulihan kata sandi (`F-13`) bagi user yang lupa password menggunakan pengiriman link / token reset ke email terdaftar.
* **Scope / Checklist**:
  - [x] Form permintaan reset password (`resources/views/auth/forgot-password.blade.php`)
  - [ ] Controller pengiriman link / OTP reset password (`PasswordResetLinkController`)
  - [ ] Form input password baru (`resources/views/auth/reset-password.blade.php`)
  - [ ] Handler update password baru (`NewPasswordController`)
  - [ ] Testing alur reset password

---

### [#7] [Setup] Setup Tech Stack & Inisialisasi Proyek Laravel
* **PIC**: Muhammad Fikhlas Hakim Arya Maulana (187241034) - `@fikhlassm`
* **Status**: Done
* **Deskripsi**: Setup fondasi project awal TwinBug meliputi penentuan tech stack, inisialisasi framework Laravel, dan struktur konfigurasi dasar.
* **Scope / Checklist**:
  - [x] Penentuan stack teknologi (PHP, Laravel, Tailwind CSS, Vite)
  - [x] Inisialisasi repository dan bootstrap project Laravel
  - [x] Setup struktur database awal (Users, Sessions, Migrations)
  - [x] Konfigurasi environment awal

---

### [#8] [F-05] Fitur Upload Screenshot Bukti Bug (Upload Screenshot on Bug Report)
* **PIC**: Abdul Hakim Fatur Rochman (187241111) - `@AbdulHakimFaturR`
* **Labels**: `enhancement`
* **Status**: In Progress
* **Deskripsi**: Implementasi fitur pengunggahan tangkapan layar/screenshot bukti permasalahan (`F-05`) pada form pelaporan bug sistem TwinBug, sehingga pelapor dapat menyertakan file gambar pendukung kendala teknis.
* **Scope / Checklist**:
  - [x] Input field file upload screenshot pada form laporan bug (`resources/views/bug-reports/create.blade.php`)
  - [x] Validasi file upload (format mimes: PNG, JPG, GIF, WebP & ukuran file max: 5MB di `StoreBugReportRequest`)
  - [x] Logic penyimpanan file screenshot ke storage disk publik (`BugReportController@store`)
  - [x] Penyimpanan metadata screenshot (path, original name, size, dimensions) ke tabel `bug_reports`
  - [ ] Tampilan preview screenshot pada detail dan daftar laporan bug
  - [ ] Automated feature test untuk validasi dan alur upload screenshot
* **Catatan / Kendala**: Memastikan konfigurasi symlink storage (`php artisan storage:link`) dan batas ukuran berkas agar tidak membebani performa server.

---

### [#9] [Integration] Integrasi & Upload Fitur Sistem (Register, Login, & Konsistensi UI/UX)
* **PIC**: Ivan Raditya Prisetyo (187241072) - `@Ivan-Raditya`
* **Labels**: `enhancement`
* **Status**: Done / In Progress
* **Deskripsi**: Melakukan upload dan integrasi modul fitur utama (Registrasi, Login, dan tata letak antarmuka sistem TwinBug) ke dalam repositori serta penyesuaian konsistensi UI/UX dengan acuan desain.
* **Scope / Checklist**:
  - [x] Upload dan integrasi modul autentikasi awal (Register, Login)
  - [x] Penyesuaian layout dan antarmuka komponen form sesuai acuan desain sistem
  - [x] Pengecekan konsistensi styling antar halaman antarmuka
  - [x] Review dan sinkronisasi branch dengan pengerjaan anggota tim
* **Catatan / Kendala**: Pada tahap awal pengembangan UI/UX, tata letak antarmuka masih belum konsisten sehingga memerlukan pengecekan dan penyesuaian secara berkala.

---

### [#10] [CI/CD] Setup GitHub Actions Workflow untuk Automated Testing (All Workflows)
* **PIC**: Ivan Raditya Prisetyo (187241072) - `@Ivan-Raditya`
* **Labels**: `enhancement`
* **Status**: In Progress
* **Deskripsi**: Mengonfigurasi workflow CI/CD otomatis menggunakan GitHub Actions pada repositori TwinBug untuk menjalankan automated test dan static analysis pada setiap push dan pull request.
* **Scope / Checklist**:
  - [x] Buat konfigurasi workflow GitHub Actions (`.github/workflows/ci.yml`)
  - [x] Setup PHP 8.3/8.4 environment & Composer dependency cache
  - [x] Konfigurasi environment database SQLite in-memory untuk testing
  - [x] Menjalankan test suite otomatis (`php artisan test`)
  - [x] Pengecekan formatting kode menggunakan Laravel Pint
* **Catatan / Kendala**: Workflow terhubung langsung ke dashboard [All workflows](https://github.com/AgilePemdigi-2026/TwinBug/actions).


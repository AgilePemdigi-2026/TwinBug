# TwinBug - Multimodal Bug Intelligence System

Sistem web cerdas yang dirancang untuk mendukung deteksi laporan bug duplikat menggunakan pendekatan **multimodal** (teks dan visual tangkapan layar/artefak).

Dokumentasi ini mencakup implementasi antarmuka **Login** dan **Register** yang dibangun secara presisi sesuai spesifikasi desain Figma (Sage Theme).

---

## 🎨 Spesifikasi Desain & Visual (Sage Theme)

- **Palette Warna**:
  - Background Canvas: Warm Parchment & Subtle Gradient (`#FAF6F0`)
  - Kartu & Kontainer: Pure White (`#FFFFFF`) dengan rounded corner 20px - 24px
  - Sage Green Primary: `#4A7C59`
  - Aksen Gold / Ochre: `#705C30` & `#F0E8DB`
  - Ambient Orbs: Efek blur ambient hangat dan mint (`#C8E8D0`, `#F8E0A8`, `rgba(74, 124, 89, 0.08)`)
- **Tipografi**:
  - Heading & Logo: **Literata** (Serif elegan, optical size)
  - Body & UI Controls: **Nunito Sans** (Sangat mudah dibaca pada berbagai ukuran layar)

---

## ⚡ Integrasi Backend Supabase

Proyek ini telah terhubung langsung dengan backend **Supabase**:
- **Project URL**: `https://ziezgigbbixahdcnhgnj.supabase.co`
- **Dashboard**: `https://supabase.com/dashboard/project/ziezgigbbixahdcnhgnj`
- **Supabase Auth**: Pendaftaran (`signUp`), login (`signInWithPassword`), permintaan pemulihan kata sandi (`resetPasswordForEmail`), dan pembaruan kata sandi baru (`updateUser`) dengan token JWT dan penanganan sesi.
- **PostgreSQL Schema**: Tabel `public.profiles` dengan trigger otomatis `on_auth_user_created` yang menyinkronkan data pengguna (`id`, `full_name`, `email`, `role`, `created_at`, `updated_at`) setiap kali user mendaftar.
- **Halaman Pemulihan Kata Sandi**:
  - **Permintaan Reset** (`#forgot-password`): Mengirimkan email tautan pemulihan resmi melalui Supabase.
  - **Buat Kata Sandi Baru** (`#reset-password`): Form pengaturan kata sandi baru dengan verifikasi kecocokan, toggle visibilitas, dan indikator kekuatan kata sandi interaktif.
- **Akun Demo Bawaan**:
  - **Reporter**: `reporter.demo@gmail.com` (Sandi: `TwinBug@Demo2025!`)
  - **Developer**: `developer.demo@gmail.com` (Sandi: `TwinBug@Demo2025!`)
- **Pengujian Nyata**: Berhasil melakukan registrasi akun baru (`budi.santoso.qa@gmail.com` - Role: Developer), pengujian login ke dasbor, serta alur pemulihan kata sandi.

### 1. Halaman Masuk (Login)
- **Badge Header**: `Multimodal Bug Intelligence System`
- **Bidang Input**:
  - `Email atau Username` (dengan ikon profil)
  - `Kata Sandi` (dengan ikon gembok & toggle tampilkan/sembunyikan kata sandi)
  - Tautan *"Lupa kata sandi?"*
  - Checkbox *"Ingat saya di perangkat ini"*
- **Tombol Utama (CTA)**: `Masuk ke Sistem ->` dengan efek hover dan transisi halus
- **Demo Role Schnellzugriff (Akses Cepat)**:
  - Tombol **Reporter**: Otomatis mengisi email & kata sandi reporter
  - Tombol **Developer**: Otomatis mengisi email & kata sandi developer
- **Tautan Navigasi**: *"Belum memiliki akun? Daftar Sekarang"*
- **Footer Keamanan**: `Enkripsi AES-256` • `SSO Enterprise Siap`

### 2. Halaman Pendaftaran (Register)
- **Badge Status**: Titik hijau bernyawa + `PENDAFTARAN AKUN BARU`
- **Heading**: `Buat Akun TwinBug`
- **Form Grid 2 Kolom**:
  - `Nama Lengkap` (Wajib)
  - `Email` (Domain Kerja)
- **Bespoke Interactive Role Selector**:
  - Pilihan role interaktif **Reporter** dan **Developer** dengan indikator radio kustom
- **Keamanan Kata Sandi 2 Kolom**:
  - `Kata Sandi` & `Konfirmasi Kata Sandi` lengkap dengan toggle show/hide
- **Password Strength Tracker Interaktif**:
  - Evaluasi real-time tingkat kekuatan kata sandi (*Belum terisi*, *Lemah*, *Sedang*, *Kuat*, *Sangat Kuat*)
  - 4 bar indikator dinamis dengan pewarnaan adaptif
  - Panduan syarat karakter (panjang minimal 8, kombinasi huruf besar-kecil, angka, simbol)
- **Checkbox Persetujuan Ketentuan Layanan & Privasi** terkait pemrosesan dataset telemetri bug
- **Tombol Utama (CTA)**: `Daftar Akun Baru ->`
- **Tautan Navigasi**: *"Sudah memiliki akun? Masuk di sini"*
- **Footer Kepatuhan**: `© 2025 TwinBug Engine` • `Enterprise Grade Security` • `SOC2 Compliant`

### 3. Sistem Notifikasi & Transisi
- Animasi transisi instan dan mulus antara Login dan Register via URL Hash (`#login` & `#register`)
- Toast notifikasi pop-up untuk validasi form dan feedback aksi pengguna

---

---

## 📂 Struktur Proyek (Next.js + Supabase)

```
Twin Bug/
├── app/
│   ├── layout.jsx            # Root layout Next.js, metadata, ambient orbs, dan ToastProvider
│   ├── globals.css           # Design tokens Sage Theme, tipografi Literata & Nunito, animasi
│   ├── page.jsx              # SPA Landing/Auth view switcher tersinkronisasi hash
│   ├── login/page.jsx        # Route langsung ke halaman Login (/login)
│   ├── register/page.jsx     # Route langsung ke halaman Register (/register)
│   ├── forgot-password/page.jsx # Route pemulihan kata sandi (/forgot-password)
│   ├── reset-password/page.jsx  # Route pengaturan sandi baru (/reset-password)
│   └── dashboard/page.jsx    # Route dasbor sesi aktif (/dashboard)
├── components/
│   ├── LoginForm.jsx         # Komponen form login + quick demo autofill
│   ├── RegisterForm.jsx      # Komponen form register + role picker + strength tracker
│   ├── ForgotPasswordForm.jsx# Komponen form request reset kata sandi
│   ├── ResetPasswordForm.jsx # Komponen form update password baru
│   ├── DashboardView.jsx     # Komponen tampilan profil & status sesi Supabase
│   └── Toast.jsx             # Sistem notifikasi toast interaktif berbasis React Context
├── lib/
│   └── supabase.js           # Supabase Client & Helper Services (@supabase/supabase-js)
├── .env.local                # Kredensial lokal Supabase (URL & Anon Key)
├── .env.example              # Template variabel lingkungan
├── next.config.mjs           # Konfigurasi Next.js
├── package.json              # Konfigurasi dependensi (Next.js 16, React 19, Supabase JS)
└── README.md                 # Dokumentasi proyek
```

---

## 💻 Cara Menjalankan Secara Lokal

1. **Jalankan development server Next.js:**
   ```bash
   npm run dev
   ```
2. **Buka browser pada:**
   - **Halaman Utama**: [http://localhost:3000](http://localhost:3000)
   - **Login**: [http://localhost:3000/login](http://localhost:3000/login) atau `http://localhost:3000/#login`
   - **Register**: [http://localhost:3000/register](http://localhost:3000/register) atau `http://localhost:3000/#register`
   - **Lupa Password**: [http://localhost:3000/forgot-password](http://localhost:3000/forgot-password)
   - **Dasbor**: [http://localhost:3000/dashboard](http://localhost:3000/dashboard)

3. **Membangun versi produksi (Build):**
   ```bash
   npm run build
   npm start
   ```

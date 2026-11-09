<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class FaqController extends Controller
{
    /**
     * Tampilkan halaman pusat bantuan dan tanya jawab (FAQ).
     */
    public function index(Request $request): View
    {
        $categories = [
            'all' => 'Semua Topik',
            'account' => 'Akun & OTP',
            'reporting' => 'Pelaporan Bug',
            'media' => 'Screenshot & Media',
            'duplicate' => 'Deteksi Duplikasi',
            'workflow' => 'Status & Siklus Penanganan',
        ];

        $faqs = [
            [
                'id' => 'faq-1',
                'category' => 'account',
                'category_label' => 'Akun & OTP',
                'question' => 'Bagaimana alur pendaftaran akun di TwinBug?',
                'answer' => 'Pengguna mendaftar melalui formulir registrasi dengan mengisi nama lengkap, alamat email aktif, kata sandi, dan memilih peran (Reporter atau Developer). Setelah formulir dikirim, sistem akan mengirimkan kode OTP 6-digit ke email Anda untuk verifikasi identitas.',
                'keywords' => 'daftar registrasi akun verifikasi email peran reporter developer',
            ],
            [
                'id' => 'faq-2',
                'category' => 'account',
                'category_label' => 'Akun & OTP',
                'question' => 'Mengapa saya belum menerima kode OTP verifikasi?',
                'answer' => 'Pengiriman kode OTP biasanya memerlukan waktu beberapa detik hingga 2 menit. Jika belum masuk, periksa folder <strong>Spam</strong> atau <strong>Junk</strong> di email Anda. Pastikan juga alamat surel yang dimasukkan tidak salah ketik. Kode OTP berlaku selama 10 menit.',
                'keywords' => 'otp token tidak masuk email spam kadaluarsa verifikasi gagal',
            ],
            [
                'id' => 'faq-3',
                'category' => 'account',
                'category_label' => 'Akun & OTP',
                'question' => 'Apa perbedaan peran antara Reporter dan Developer?',
                'answer' => '<strong>Reporter</strong> fokus pada pengujian, pembuatan laporan bug baru, unggah bukti screenshot, dan memantau status laporan miliknya sendiri.<br><br><strong>Developer</strong> memiliki akses ke seluruh antrean laporan bug lintas sistem, meninjau detail teknis & bukti visual, serta memperbarui siklus status bug (Open &rarr; In Progress &rarr; Resolved &rarr; Closed).',
                'keywords' => 'peran role reporter developer hak akses wewenang perbedaan',
            ],
            [
                'id' => 'faq-4',
                'category' => 'account',
                'category_label' => 'Akun & OTP',
                'question' => 'Bagaimana cara memulihkan akun jika lupa kata sandi?',
                'answer' => 'Buka halaman Masuk, lalu klik tautan <strong>"Lupa kata sandi?"</strong>. Masukkan alamat email terdaftar, dan sistem akan mengirimkan tautan reset kata sandi bertanda tangan aman ke kotak masuk surel Anda.',
                'keywords' => 'lupa kata sandi reset password lupa email pemulihan',
            ],
            [
                'id' => 'faq-5',
                'category' => 'reporting',
                'category_label' => 'Pelaporan Bug',
                'question' => 'Bagaimana format penulisan laporan bug yang baik?',
                'answer' => 'Laporan yang baik memudahkan tim pengembang mereproduksi kendala dengan cepat. Gunakan:<br>• <strong>Judul ringkas & spesifik</strong> (contoh: <em>"Tombol submit form checkout tidak merespons di Safari"</em>)<br>• <strong>Modul terdampak</strong> (misal: <em>Autentikasi, Pelaporan, Profil</em>)<br>• <strong>Langkah reproduksi</strong> nomor per nomor yang runtut<br>• <strong>Tingkat keparahan (severity)</strong> yang sesuai dampak nyata ke pengguna.',
                'keywords' => 'format laporan cara buat modul reproduksi deskripsi efektif',
            ],
            [
                'id' => 'faq-6',
                'category' => 'reporting',
                'category_label' => 'Pelaporan Bug',
                'question' => 'Bagaimana menentukan tingkat keparahan (Severity) bug?',
                'answer' => 'TwinBug membagi keparahan ke dalam 4 tingkatan:<br>• <span class="font-semibold text-label">Low:</span> Masalah minor kosmetik / typo visual tanpa mengganggu fungsi sistem.<br>• <span class="font-semibold text-warning">Medium:</span> Masalah fungsional tetapi masih ada jalan pintas (workaround).<br>• <span class="font-semibold text-ochre">High:</span> Fungsi utama terdampak signifikan pada modul penting.<br>• <span class="font-semibold text-danger">Critical:</span> Kerusakan total sistem, kegagalan keamanan, atau kehilangan data fatal.',
                'keywords' => 'severity keparahan low medium high critical tingkat dampak',
            ],
            [
                'id' => 'faq-7',
                'category' => 'media',
                'category_label' => 'Screenshot & Media',
                'question' => 'Format dan batas ukuran file screenshot apa saja yang diterima?',
                'answer' => 'Sistem mendukung berkas gambar bertipe <strong>PNG, JPG, JPEG, dan WebP</strong> dengan ukuran file maksimal <strong>5 MB</strong>. Sistem akan otomatis mendeteksi resolusi dan dimensi pixel gambar saat berkas dipilih.',
                'keywords' => 'screenshot format ukuran kapasitas 5mb png jpg webp dimensi gambar',
            ],
            [
                'id' => 'faq-8',
                'category' => 'media',
                'category_label' => 'Screenshot & Media',
                'question' => 'Apakah melampirkan screenshot bukti bug bersifat wajib?',
                'answer' => 'Melampirkan bukti screenshot sangat dianjurkan untuk mempercepat investigasi visual, namun tidak bersifat wajib jika bug berkaitan dengan logika backend atau kesalahan yang tidak memunculkan output visual langsung.',
                'keywords' => 'screenshot wajib bukti gambar attachment opsional',
            ],
            [
                'id' => 'faq-9',
                'category' => 'duplicate',
                'category_label' => 'Deteksi Duplikasi',
                'question' => 'Apa itu fitur deteksi duplikasi (Twin Bug Detection)?',
                'answer' => 'Deteksi duplikasi adalah kapabilitas cerdas TwinBug untuk mencegah pelaporan berulang atas kendala yang sama. Sistem menganalisis kesamaan teks judul, modul, deskripsi semantik, dan bukti tangkapan layar untuk mengelompokkan laporan yang identik ke dalam satu thread penanganan.',
                'keywords' => 'duplikasi duplicate twin bug kembar deteksi cerdas ai multimodal semantik',
            ],
            [
                'id' => 'faq-10',
                'category' => 'duplicate',
                'category_label' => 'Deteksi Duplikasi',
                'question' => 'Apa yang harus dilakukan jika melihat laporan yang mirip di Daftar Laporan?',
                'answer' => 'Sebelum membuat laporan baru, periksa terlebih dahulu halaman <strong>Daftar Laporan</strong> menggunakan filter modul atau kata kunci. Jika bug yang sama sudah dilaporkan dan berstatus <em>Open</em> atau <em>In Progress</em>, Anda tidak perlu mengirimkan laporan ganda.',
                'keywords' => 'laporan mirip cari cek filter daftar laporan duplikat ganda',
            ],
            [
                'id' => 'faq-11',
                'category' => 'workflow',
                'category_label' => 'Status & Siklus Penanganan',
                'question' => 'Apa arti status pada setiap laporan bug?',
                'answer' => 'Setiap laporan mengalami transisi status:<br>• <span class="inline-block rounded-md bg-gold-100 px-2 py-0.5 text-xs font-semibold text-ochre">Open</span>: Laporan baru diterima dan menunggu triase pengembang.<br>• <span class="inline-block rounded-md bg-sage-100 px-2 py-0.5 text-xs font-semibold text-sage-700">In Progress</span>: Bug sedang diinvestigasi atau dalam tahap perbaikan kode.<br>• <span class="inline-block rounded-md bg-emerald-100 px-2 py-0.5 text-xs font-semibold text-success">Resolved</span>: Perbaikan telah selesai dan menunggu verifikasi akhir.<br>• <span class="inline-block rounded-md bg-input px-2 py-0.5 text-xs font-semibold text-label">Closed</span>: Siklus penanganan selesai dan kasus ditutup secara tuntas.',
                'keywords' => 'status open in progress resolved closed siklus alur arti',
            ],
            [
                'id' => 'faq-12',
                'category' => 'workflow',
                'category_label' => 'Status & Siklus Penanganan',
                'question' => 'Di mana Reporter dapat memantau status tindak lanjut bug yang dilaporkan?',
                'answer' => 'Reporter dapat melihat rekapitulasi jumlah laporan berdasarkan status di <strong>Dashboard</strong> utama, atau membuka menu <strong>Daftar Laporan</strong> untuk melihat rincian kronologi, catatan pengembang, dan status terkini.',
                'keywords' => 'pantau tracking monitor dashboard progres tindak lanjut',
            ],
        ];

        return view('faq', compact('categories', 'faqs'));
    }
}

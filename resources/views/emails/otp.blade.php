@component('mail::message', ['theme' => 'default'])
<h1>{{ $title }}</h1>
<p>Kode verifikasi OTP Anda adalah: <strong style="font-size:24px; letter-spacing:5px;">{{ $otp }}</strong></p>
<p>Kode ini akan expired dalam {{ $expires }}.</p>
<p>Jika Anda tidak meminta kode ini, abaikan email ini.</p>
{{-- Alamat email pengirim akan tampil di klien email Anda --}}
@endcomponent
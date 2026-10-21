<x-mail::message>
# {{ $title }}

Kode verifikasi OTP Anda adalah:

<x-mail::panel>
<span style="font-size: 24px; letter-spacing: 5px; font-weight: 700;">{{ $otp }}</span>
</x-mail::panel>

Kode ini akan kedaluwarsa dalam {{ $expires }}.

Jika Anda tidak meminta kode ini, abaikan email ini.

Terima kasih,<br>
{{ config('app.name') }}
</x-mail::message>

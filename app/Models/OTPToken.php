<?php

namespace App\Models;

use Database\Factories\OTPTokenFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OTPToken extends Model
{
    /** @use HasFactory<OTPTokenFactory> */
    use HasFactory;

    protected $table = 'otp_tokens';

    protected $fillable = [
        'email',
        'otp_code',
        'expires_at',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'expires_at' => 'datetime',
        ];
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OTPToken extends Model
{
    /** @use HasFactory<\Database\Factories\OTPTokenFactory> */
    use HasFactory;

    protected $table = 'otp_tokens';

    protected $fillable = [
        'email',
        'otp_code',
        'expires_at',
    ];
}

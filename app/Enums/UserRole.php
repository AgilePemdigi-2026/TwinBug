<?php

namespace App\Enums;

enum UserRole: string
{
    case Reporter = 'reporter';
    case Developer = 'developer';

    public function label(): string
    {
        return match ($this) {
            self::Reporter => 'Reporter',
            self::Developer => 'Developer',
        };
    }

    public function description(): string
    {
        return match ($this) {
            self::Reporter => 'Melaporkan bug dengan detail dan langkah reproduksi yang jelas.',
            self::Developer => 'Menelusuri, memperbaiki, dan memverifikasi laporan bug.',
        };
    }
}

<?php

namespace App\Enums;

enum DetectionStatus: string
{
    case Pending = 'pending';
    case Processing = 'processing';
    case Completed = 'completed';
    case Failed = 'failed';

    public function label(): string
    {
        return match ($this) {
            self::Pending => 'Menunggu',
            self::Processing => 'Sedang Diproses',
            self::Completed => 'Selesai',
            self::Failed => 'Gagal',
        };
    }

    public function badgeClasses(): string
    {
        return match ($this) {
            self::Pending => 'bg-slate-100 text-slate-700',
            self::Processing => 'bg-amber-100 text-amber-800',
            self::Completed => 'bg-sage-200 text-sage-800',
            self::Failed => 'bg-danger/10 text-danger',
        };
    }
}

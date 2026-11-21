<?php

namespace App\Enums;

enum OcrStatus: string
{
    case Pending = 'pending';
    case Processing = 'processing';
    case Completed = 'completed';
    case Failed = 'failed';
    case Skipped = 'skipped';

    public function label(): string
    {
        return match ($this) {
            self::Pending => 'Menunggu OCR',
            self::Processing => 'Sedang Diproses OCR',
            self::Completed => 'OCR Selesai',
            self::Failed => 'OCR Gagal',
            self::Skipped => 'OCR Dilewati',
        };
    }

    public function badgeClasses(): string
    {
        return match ($this) {
            self::Pending => 'bg-slate-100 text-slate-700',
            self::Processing => 'bg-amber-100 text-amber-800',
            self::Completed => 'bg-sage-200 text-sage-800',
            self::Failed => 'bg-danger/10 text-danger',
            self::Skipped => 'bg-input text-label',
        };
    }
}

<?php

namespace App\Enums;

enum AnalysisStatus: string
{
    case Pending = 'pending';
    case Processing = 'processing';
    case Completed = 'completed';
    case Failed = 'failed';

    public function label(): string
    {
        return match ($this) {
            self::Pending => 'Menunggu Analisis',
            self::Processing => 'Sedang Diproses',
            self::Completed => 'Analisis Selesai',
            self::Failed => 'Analisis Gagal',
        };
    }

    public function badgeClasses(): string
    {
        return match ($this) {
            self::Pending => 'bg-input text-label',
            self::Processing => 'bg-gold-100 text-ochre',
            self::Completed => 'bg-sage-100 text-sage-700',
            self::Failed => 'bg-danger/10 text-danger',
        };
    }

    public function isStillProcessing(): bool
    {
        return $this === self::Pending || $this === self::Processing;
    }
}

<?php

namespace App\Enums;

enum BugSeverity: string
{
    case Low = 'low';
    case Medium = 'medium';
    case High = 'high';
    case Critical = 'critical';

    public function label(): string
    {
        return match ($this) {
            self::Low => 'Rendah',
            self::Medium => 'Sedang',
            self::High => 'Tinggi',
            self::Critical => 'Kritis',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Low => 'sage',
            self::Medium => 'gold',
            self::High => 'warning',
            self::Critical => 'danger',
        };
    }

    public function badgeClasses(): string
    {
        return match ($this) {
            self::Low => 'bg-sage-100 text-sage-700',
            self::Medium => 'bg-gold-100 text-ochre',
            self::High => 'bg-warning/15 text-warning',
            self::Critical => 'bg-danger/10 text-danger',
        };
    }
}

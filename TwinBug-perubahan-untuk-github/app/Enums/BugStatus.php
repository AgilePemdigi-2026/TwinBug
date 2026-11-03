<?php

namespace App\Enums;

enum BugStatus: string
{
    case Open = 'open';
    case InProgress = 'in_progress';
    case Duplicate = 'duplicate';
    case Resolved = 'resolved';
    case Closed = 'closed';

    public function label(): string
    {
        return match ($this) {
            self::Open => 'Terbuka',
            self::InProgress => 'Diproses',
            self::Duplicate => 'Duplicate',
            self::Resolved => 'Diselesaikan',
            self::Closed => 'Ditutup',
        };
    }

    public function badgeClasses(): string
    {
        return match ($this) {
            self::Open => 'bg-sage-200 text-sage-700',
            self::InProgress => 'bg-gold-100 text-ochre',
            self::Duplicate => 'bg-warning/15 text-warning',
            self::Resolved => 'bg-sage-100 text-sage-700',
            self::Closed => 'bg-input text-label',
        };
    }
}

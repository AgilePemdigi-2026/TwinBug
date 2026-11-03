<?php

namespace App\Enums;

enum BugStatus: string
{
    case Open = 'open';
    case InProgress = 'in_progress';
    case Resolved = 'resolved';
    case Closed = 'closed';

    public function label(): string
    {
        return match ($this) {
            self::Open => 'Terbuka',
            self::InProgress => 'Diproses',
            self::Resolved => 'Diselesaikan',
            self::Closed => 'Ditutup',
        };
    }

    public function badgeClasses(): string
    {
        return match ($this) {
            self::Open => 'bg-sky-100 text-sky-800',
            self::InProgress => 'bg-amber-100 text-amber-800',
            self::Resolved => 'bg-sage-200 text-sage-800',
            self::Closed => 'bg-slate-200 text-slate-700',
        };
    }
}

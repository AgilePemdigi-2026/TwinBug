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
}

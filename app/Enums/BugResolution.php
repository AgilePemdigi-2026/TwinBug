<?php

namespace App\Enums;

enum BugResolution: string
{
    case Fixed = 'fixed';
    case Duplicate = 'duplicate';
    case CannotReproduce = 'cannot_reproduce';
    case WontFix = 'wont_fix';
    case ByDesign = 'by_design';

    public function label(): string
    {
        return match ($this) {
            self::Fixed => 'Telah Diperbaiki',
            self::Duplicate => 'Duplikat / Serupa',
            self::CannotReproduce => 'Tidak Dapat Direproduksi',
            self::WontFix => 'Tidak Akan Diperbaiki',
            self::ByDesign => 'Sesuai Desain',
        };
    }

    public function badgeClasses(): string
    {
        return match ($this) {
            self::Fixed => 'bg-emerald-100 text-emerald-800',
            self::Duplicate => 'bg-purple-100 text-purple-800',
            self::CannotReproduce => 'bg-amber-100 text-amber-800',
            self::WontFix => 'bg-rose-100 text-rose-800',
            self::ByDesign => 'bg-slate-200 text-slate-700',
        };
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

#[Fillable(['name', 'code'])]
class Application extends Model
{
    use HasFactory;

    protected static function booted(): void
    {
        static::creating(function (Application $application): void {
            $application->code ??= self::generateCode();
        });
    }

    /**
     * Get the users connected to this application.
     *
     * @return HasMany<User, $this>
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    /**
     * Get the bug reports filed under this application.
     *
     * @return HasMany<BugReport, $this>
     */
    public function bugReports(): HasMany
    {
        return $this->hasMany(BugReport::class);
    }

    /**
     * Generate a unique invitation code.
     */
    public static function generateCode(): string
    {
        do {
            $code = Str::upper(Str::random(8));
        } while (self::where('code', $code)->exists());

        return $code;
    }
}

<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ArticleVisitorSession extends Model
{
    public $timestamps = false;

    protected $guarded = [];

    protected $casts = [
        'date' => 'date',
        'created_at' => 'datetime',
    ];

    public function article(): BelongsTo
    {
        return $this->belongsTo(Article::class);
    }

    /**
     * Generuj hash sesji (bez przechowywania IP - RODO).
     */
    public static function generateSessionHash(): string
    {
        // Uzyj ID sesji Laravel jako podstawy
        $sessionId = session()->getId();

        // Dodaj user agent dla lepszej unikalnosci
        $userAgent = request()->userAgent() ?? '';

        // Hash bez IP - zgodne z RODO
        return hash('sha256', $sessionId . $userAgent . config('app.key'));
    }

    /**
     * Usun stare wpisy (starsze niz N dni).
     */
    public static function cleanupOld(int $days = 90): int
    {
        return self::where('date', '<', now()->subDays($days))->delete();
    }
}

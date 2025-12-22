<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Category extends Model
{
    protected $guarded = [];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function ($category) {
            if (empty($category->slug)) {
                $category->slug = Str::slug($category->name);
            }
        });
    }

    public function articles(): HasMany
    {
        return $this->hasMany(Article::class);
    }

    public function publishedArticles(): HasMany
    {
        return $this->hasMany(Article::class)
            ->where('is_published', true)
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now());
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeNews($query)
    {
        return $query->where('type', 'news');
    }

    public function scopeMedical($query)
    {
        return $query->where('type', 'medical');
    }
}

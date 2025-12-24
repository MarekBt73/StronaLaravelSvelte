<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Article extends Model
{
    protected $guarded = [];

    protected $casts = [
        'is_published' => 'boolean',
        'is_featured' => 'boolean',
        'published_at' => 'datetime',
    ];

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function ($article) {
            if (empty($article->slug)) {
                $article->slug = Str::slug($article->title);
            }
        });
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function featuredMedia(): BelongsTo
    {
        return $this->belongsTo(Media::class, 'featured_image_id');
    }

    public function dailyViews(): HasMany
    {
        return $this->hasMany(ArticleView::class);
    }

    public function visitorSessions(): HasMany
    {
        return $this->hasMany(ArticleVisitorSession::class);
    }

    /**
     * Get the featured image URL (from media library or direct upload).
     */
    public function getFeaturedImageUrlAttribute(): ?string
    {
        if ($this->featured_image_id && $this->featuredMedia) {
            return $this->featuredMedia->url;
        }

        if ($this->featured_image) {
            return asset('storage/' . $this->featured_image);
        }

        return null;
    }

    /**
     * Get responsive image URLs for featured image.
     */
    public function getFeaturedImageSrcsetAttribute(): ?array
    {
        if (! $this->featured_image_id || ! $this->featuredMedia) {
            return null;
        }

        $media = $this->featuredMedia;
        $srcset = [];

        if ($media->mobile_url) {
            $srcset['mobile'] = $media->mobile_url;
        }
        if ($media->tablet_url) {
            $srcset['tablet'] = $media->tablet_url;
        }
        if ($media->desktop_url) {
            $srcset['desktop'] = $media->desktop_url;
        }

        return count($srcset) > 0 ? $srcset : null;
    }

    public function scopePublished($query)
    {
        return $query->where('is_published', true)
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now());
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeRecent($query)
    {
        return $query->orderBy('published_at', 'desc');
    }

    public function getReadingTimeAttribute(): int
    {
        $wordCount = str_word_count(strip_tags($this->content));
        return max(1, (int) ceil($wordCount / 200));
    }

    public function getFormattedDateAttribute(): string
    {
        return $this->published_at?->format('d.m.Y') ?? '';
    }
}

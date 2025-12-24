<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class Media extends Model
{
    protected $table = 'media';

    protected $guarded = [];

    protected $casts = [
        'variants' => 'array',
        'ai_generated' => 'boolean',
        'size' => 'integer',
        'width' => 'integer',
        'height' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getUrlAttribute(): string
    {
        return Storage::disk($this->disk)->url($this->path);
    }

    public function getVariantUrl(string $variant): ?string
    {
        if (! $this->variants || ! isset($this->variants[$variant])) {
            return $this->url;
        }

        return Storage::disk($this->disk)->url($this->variants[$variant]);
    }

    public function getMobileUrlAttribute(): ?string
    {
        return $this->getVariantUrl('mobile');
    }

    public function getTabletUrlAttribute(): ?string
    {
        return $this->getVariantUrl('tablet');
    }

    public function getDesktopUrlAttribute(): ?string
    {
        return $this->getVariantUrl('desktop');
    }

    public function getThumbnailUrlAttribute(): ?string
    {
        return $this->getVariantUrl('thumbnail');
    }

    public function getFullPathAttribute(): string
    {
        return Storage::disk($this->disk)->path($this->path);
    }

    public function getFormattedSizeAttribute(): string
    {
        $bytes = $this->size;

        if ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2) . ' MB';
        }

        if ($bytes >= 1024) {
            return number_format($bytes / 1024, 2) . ' KB';
        }

        return $bytes . ' B';
    }

    public function isImage(): bool
    {
        return $this->type === 'image';
    }

    public function isVideo(): bool
    {
        return $this->type === 'video';
    }

    public function isDocument(): bool
    {
        return $this->type === 'document';
    }

    public function getTagsArrayAttribute(): array
    {
        if (empty($this->tags)) {
            return [];
        }

        return array_map('trim', explode(',', $this->tags));
    }

    public function delete(): bool
    {
        // Delete main file
        Storage::disk($this->disk)->delete($this->path);

        // Delete variants
        if ($this->variants) {
            foreach ($this->variants as $variant) {
                Storage::disk($this->disk)->delete($variant);
            }
        }

        return parent::delete();
    }
}

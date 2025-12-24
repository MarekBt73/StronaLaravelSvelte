<?php

declare(strict_types=1);

namespace App\Services\Media;

use App\Models\Media;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

class MediaService
{
    private ImageManager $imageManager;

    private array $imageVariants = [
        'thumbnail' => ['width' => 150, 'height' => 150],
        'mobile' => ['width' => 480, 'height' => null],
        'tablet' => ['width' => 768, 'height' => null],
        'desktop' => ['width' => 1200, 'height' => null],
    ];

    private int $quality = 85;

    private int $maxWidth = 1920;

    private int $maxHeight = 1080;

    public function __construct()
    {
        $this->imageManager = new ImageManager(new Driver);
    }

    public function upload(UploadedFile $file, ?string $folder = null): Media
    {
        $type = $this->determineType($file);
        $name = $this->generateName($file);
        $folder = $folder ?? date('Y/m');

        if ($type === 'image') {
            return $this->processImage($file, $name, $folder);
        }

        return $this->processFile($file, $name, $folder, $type);
    }

    private function processImage(UploadedFile $file, string $name, string $folder): Media
    {
        $image = $this->imageManager->read($file->getPathname());
        $originalWidth = $image->width();
        $originalHeight = $image->height();

        // Resize if too large
        if ($originalWidth > $this->maxWidth || $originalHeight > $this->maxHeight) {
            $image->scaleDown($this->maxWidth, $this->maxHeight);
        }

        // Generate unique filename
        $baseName = Str::slug(pathinfo($name, PATHINFO_FILENAME));
        $extension = 'webp';
        $fileName = "{$baseName}.{$extension}";
        $path = "media/{$folder}/{$fileName}";

        // Ensure unique path
        $counter = 1;
        while (Storage::disk('public')->exists($path)) {
            $fileName = "{$baseName}-{$counter}.{$extension}";
            $path = "media/{$folder}/{$fileName}";
            $counter++;
        }

        // Save main image as WebP
        $encodedImage = $image->toWebp($this->quality);
        Storage::disk('public')->put($path, (string) $encodedImage);

        // Generate variants
        $variants = $this->generateVariants($image, $folder, $baseName);

        return Media::create([
            'name' => $name,
            'file_name' => $fileName,
            'mime_type' => 'image/webp',
            'disk' => 'public',
            'path' => $path,
            'size' => Storage::disk('public')->size($path),
            'type' => 'image',
            'width' => $image->width(),
            'height' => $image->height(),
            'variants' => $variants,
            'user_id' => auth()->id(),
            'folder' => $folder,
        ]);
    }

    private function generateVariants($image, string $folder, string $baseName): array
    {
        $variants = [];
        $originalWidth = $image->width();

        foreach ($this->imageVariants as $variantName => $dimensions) {
            // Skip variants larger than original
            if ($dimensions['width'] >= $originalWidth && $variantName !== 'thumbnail') {
                continue;
            }

            $variantImage = clone $image;

            if ($variantName === 'thumbnail') {
                // Thumbnail: crop to square
                $variantImage->cover($dimensions['width'], $dimensions['height']);
            } else {
                // Others: scale down maintaining aspect ratio
                $variantImage->scaleDown($dimensions['width'], $dimensions['height']);
            }

            $variantFileName = "{$baseName}-{$variantName}.webp";
            $variantPath = "media/{$folder}/{$variantFileName}";

            $encoded = $variantImage->toWebp($this->quality);
            Storage::disk('public')->put($variantPath, (string) $encoded);

            $variants[$variantName] = $variantPath;
        }

        return $variants;
    }

    private function processFile(UploadedFile $file, string $name, string $folder, string $type): Media
    {
        $extension = $file->getClientOriginalExtension();
        $baseName = Str::slug(pathinfo($name, PATHINFO_FILENAME));
        $fileName = "{$baseName}.{$extension}";
        $path = "media/{$folder}/{$fileName}";

        // Ensure unique path
        $counter = 1;
        while (Storage::disk('public')->exists($path)) {
            $fileName = "{$baseName}-{$counter}.{$extension}";
            $path = "media/{$folder}/{$fileName}";
            $counter++;
        }

        Storage::disk('public')->putFileAs("media/{$folder}", $file, $fileName);

        return Media::create([
            'name' => $name,
            'file_name' => $fileName,
            'mime_type' => $file->getMimeType(),
            'disk' => 'public',
            'path' => $path,
            'size' => $file->getSize(),
            'type' => $type,
            'user_id' => auth()->id(),
            'folder' => $folder,
        ]);
    }

    private function determineType(UploadedFile $file): string
    {
        $mime = $file->getMimeType();

        if (str_starts_with($mime, 'image/')) {
            return 'image';
        }

        if (str_starts_with($mime, 'video/')) {
            return 'video';
        }

        return 'document';
    }

    private function generateName(UploadedFile $file): string
    {
        return pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
    }

    public function reprocessImage(Media $media): Media
    {
        if (! $media->isImage()) {
            return $media;
        }

        $fullPath = $media->full_path;

        if (! file_exists($fullPath)) {
            return $media;
        }

        $image = $this->imageManager->read($fullPath);

        // Delete old variants
        if ($media->variants) {
            foreach ($media->variants as $variantPath) {
                Storage::disk($media->disk)->delete($variantPath);
            }
        }

        // Generate new variants
        $baseName = pathinfo($media->file_name, PATHINFO_FILENAME);
        $variants = $this->generateVariants($image, $media->folder, $baseName);

        $media->update([
            'variants' => $variants,
            'width' => $image->width(),
            'height' => $image->height(),
        ]);

        return $media;
    }

    public function setQuality(int $quality): self
    {
        $this->quality = max(1, min(100, $quality));

        return $this;
    }

    public function setMaxDimensions(int $width, int $height): self
    {
        $this->maxWidth = $width;
        $this->maxHeight = $height;

        return $this;
    }
}

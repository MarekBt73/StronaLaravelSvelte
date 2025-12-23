<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Intervention\Image\Laravel\Facades\Image;
use Intervention\Image\Encoders\WebpEncoder;
use Intervention\Image\Encoders\JpegEncoder;

class ImageService
{
    /**
     * Responsive image sizes configuration
     */
    protected array $sizes = [
        'mobile' => 480,
        'tablet' => 768,
        'desktop' => 1200,
        'original' => 1920,
    ];

    /**
     * Process uploaded image - create responsive versions in WebP format
     */
    public function processImage(string $originalPath, string $directory = 'articles'): array
    {
        $disk = Storage::disk('public');
        $fullPath = $disk->path($originalPath);

        if (!file_exists($fullPath)) {
            return ['original' => $originalPath];
        }

        $pathInfo = pathinfo($originalPath);
        $baseName = $pathInfo['filename'];
        $extension = $pathInfo['extension'] ?? 'jpg';

        $result = [
            'original' => $originalPath,
            'srcset' => [],
        ];

        foreach ($this->sizes as $sizeName => $width) {
            try {
                $image = Image::read($fullPath);
                $originalWidth = $image->width();

                // Skip if original is smaller than target size
                if ($originalWidth < $width && $sizeName !== 'mobile') {
                    continue;
                }

                // Resize maintaining aspect ratio
                $image->scaleDown(width: $width);

                // Generate WebP version
                $webpPath = "{$directory}/{$baseName}-{$sizeName}.webp";
                $webpFullPath = $disk->path($webpPath);

                $image->encode(new WebpEncoder(quality: 85))
                    ->save($webpFullPath);

                $result['srcset'][$sizeName] = [
                    'path' => $webpPath,
                    'width' => $width,
                    'url' => "/storage/{$webpPath}",
                ];

                // Also keep a fallback JPG for older browsers
                if ($sizeName === 'desktop') {
                    $jpgPath = "{$directory}/{$baseName}-{$sizeName}.jpg";
                    $jpgFullPath = $disk->path($jpgPath);

                    $image->encode(new JpegEncoder(quality: 85))
                        ->save($jpgFullPath);

                    $result['fallback'] = "/storage/{$jpgPath}";
                }
            } catch (\Exception $e) {
                \Log::warning("ImageService: Failed to process {$sizeName} for {$originalPath}: " . $e->getMessage());
                continue;
            }
        }

        return $result;
    }

    /**
     * Generate srcset attribute string for HTML
     */
    public function generateSrcset(array $processedImage): string
    {
        if (empty($processedImage['srcset'])) {
            return '';
        }

        $srcsetParts = [];
        foreach ($processedImage['srcset'] as $sizeName => $data) {
            $srcsetParts[] = "{$data['url']} {$data['width']}w";
        }

        return implode(', ', $srcsetParts);
    }

    /**
     * Generate sizes attribute for responsive images
     */
    public function generateSizes(): string
    {
        return '(max-width: 480px) 480px, (max-width: 768px) 768px, 1200px';
    }

    /**
     * Get the best source for a given breakpoint
     */
    public function getImageForSize(array $processedImage, string $size = 'desktop'): string
    {
        if (isset($processedImage['srcset'][$size])) {
            return $processedImage['srcset'][$size]['url'];
        }

        // Fallback to original
        return "/storage/{$processedImage['original']}";
    }

    /**
     * Delete all responsive versions of an image
     */
    public function deleteProcessedImages(string $originalPath, string $directory = 'articles'): void
    {
        $disk = Storage::disk('public');
        $pathInfo = pathinfo($originalPath);
        $baseName = $pathInfo['filename'];

        foreach ($this->sizes as $sizeName => $width) {
            $webpPath = "{$directory}/{$baseName}-{$sizeName}.webp";
            $jpgPath = "{$directory}/{$baseName}-{$sizeName}.jpg";

            if ($disk->exists($webpPath)) {
                $disk->delete($webpPath);
            }
            if ($disk->exists($jpgPath)) {
                $disk->delete($jpgPath);
            }
        }
    }

    /**
     * Check if GD or Imagick extension is available
     */
    public function isAvailable(): bool
    {
        return extension_loaded('gd') || extension_loaded('imagick');
    }
}

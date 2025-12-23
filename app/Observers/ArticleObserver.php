<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\Article;
use App\Services\ImageService;

class ArticleObserver
{
    public function __construct(
        protected ImageService $imageService
    ) {}

    /**
     * Handle the Article "created" event.
     */
    public function created(Article $article): void
    {
        $this->processImage($article);
    }

    /**
     * Handle the Article "updated" event.
     */
    public function updated(Article $article): void
    {
        // Only process if featured_image changed
        if ($article->isDirty('featured_image')) {
            // Delete old processed images if there was a previous image
            $originalImage = $article->getOriginal('featured_image');
            if ($originalImage) {
                $this->imageService->deleteProcessedImages($originalImage);
            }

            $this->processImage($article);
        }
    }

    /**
     * Handle the Article "deleted" event.
     */
    public function deleted(Article $article): void
    {
        if ($article->featured_image) {
            $this->imageService->deleteProcessedImages($article->featured_image);
        }
    }

    /**
     * Process featured image and store responsive versions metadata
     */
    protected function processImage(Article $article): void
    {
        if (!$article->featured_image || !$this->imageService->isAvailable()) {
            return;
        }

        try {
            $processed = $this->imageService->processImage($article->featured_image);

            if (!empty($processed['srcset'])) {
                // Store processed image data in a JSON column or separate table
                // For now, we'll just log success
                \Log::info("ArticleObserver: Processed responsive images for article #{$article->id}");
            }
        } catch (\Exception $e) {
            \Log::error("ArticleObserver: Failed to process image for article #{$article->id}: " . $e->getMessage());
        }
    }
}

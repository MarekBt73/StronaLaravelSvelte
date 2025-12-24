<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Imagick\Driver;

class OptimizeImages extends Command
{
    protected $signature = 'images:optimize {--dry-run : Show what would be done without actually doing it}';

    protected $description = 'Convert images to WebP and create responsive variants';

    private array $sizes = [
        'mobile' => 480,
        'tablet' => 768,
        'desktop' => 1200,
    ];

    private int $quality = 80;

    public function handle(): int
    {
        $imagesPath = public_path('images');
        $optimizedPath = public_path('images/optimized');
        $dryRun = $this->option('dry-run');

        if (!$dryRun && !File::exists($optimizedPath)) {
            File::makeDirectory($optimizedPath, 0755, true);
        }

        $manager = new ImageManager(new Driver());

        $files = File::files($imagesPath);
        $processedCount = 0;
        $savedBytes = 0;

        $this->info('Scanning images in ' . $imagesPath);
        $this->newLine();

        $filesToProcess = collect($files)->filter(function ($file) {
            $ext = strtolower($file->getExtension());
            return in_array($ext, ['jpg', 'jpeg', 'png']);
        });

        if ($filesToProcess->isEmpty()) {
            $this->warn('No images to process.');
            return 0;
        }

        $bar = $this->output->createProgressBar($filesToProcess->count());
        $bar->start();

        foreach ($filesToProcess as $file) {
            $filename = $file->getFilenameWithoutExtension();
            $ext = strtolower($file->getExtension());
            $originalSize = $file->getSize();

            // Skip already optimized
            if (str_contains($filename, '-mobile') || str_contains($filename, '-tablet') || str_contains($filename, '-desktop')) {
                $bar->advance();
                continue;
            }

            if ($dryRun) {
                $this->line("\n  Would process: {$file->getFilename()} ({$this->formatBytes($originalSize)})");
                $bar->advance();
                continue;
            }

            try {
                $image = $manager->read($file->getPathname());
                $originalWidth = $image->width();
                $originalHeight = $image->height();

                // Create responsive variants
                foreach ($this->sizes as $sizeName => $maxWidth) {
                    if ($originalWidth > $maxWidth) {
                        $ratio = $maxWidth / $originalWidth;
                        $newHeight = (int) ($originalHeight * $ratio);

                        $variant = $manager->read($file->getPathname())
                            ->resize($maxWidth, $newHeight);

                        $variantPath = "{$optimizedPath}/{$filename}-{$sizeName}.webp";
                        $variant->toWebp($this->quality)->save($variantPath);

                        $savedBytes += $originalSize - filesize($variantPath);
                    }
                }

                // Create full-size WebP (max 1920px)
                if ($originalWidth > 1920) {
                    $ratio = 1920 / $originalWidth;
                    $image = $image->resize(1920, (int) ($originalHeight * $ratio));
                }

                $fullPath = "{$optimizedPath}/{$filename}.webp";
                $image->toWebp($this->quality)->save($fullPath);

                $savedBytes += $originalSize - filesize($fullPath);
                $processedCount++;

            } catch (\Throwable $e) {
                $this->error("\nError processing {$file->getFilename()}: {$e->getMessage()}");
            }

            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);

        if ($dryRun) {
            $this->info("Dry run complete. Would process {$filesToProcess->count()} images.");
        } else {
            $this->info("Processed {$processedCount} images.");
            $this->info("Estimated savings: {$this->formatBytes($savedBytes)}");
            $this->newLine();
            $this->info("Optimized images saved to: {$optimizedPath}");
        }

        return 0;
    }

    private function formatBytes(int $bytes): string
    {
        if ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            return number_format($bytes / 1024, 2) . ' KB';
        }
        return $bytes . ' bytes';
    }
}

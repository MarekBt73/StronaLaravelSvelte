<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Console\Command;

class GeneratePwaIcons extends Command
{
    protected $signature = 'pwa:generate-icons';

    protected $description = 'Generate PWA icons for the application';

    public function handle(): int
    {
        if (!function_exists('imagecreatetruecolor')) {
            $this->error('GD library is not installed. Please install php-gd extension.');
            return self::FAILURE;
        }

        $sizes = [192, 512];
        $outputDir = public_path('icons');

        if (!is_dir($outputDir)) {
            mkdir($outputDir, 0755, true);
        }

        // MedVita brand color #0891b2 (cyan-600)
        $brandColor = [8, 145, 178];

        foreach ($sizes as $size) {
            $image = imagecreatetruecolor($size, $size);

            $bgColor = imagecolorallocate($image, $brandColor[0], $brandColor[1], $brandColor[2]);
            $white = imagecolorallocate($image, 255, 255, 255);

            // Fill background
            imagefilledrectangle($image, 0, 0, $size, $size, $bgColor);

            // Try to find a font
            $fontSize = (int)($size * 0.5);
            $fontFile = $this->findFont();

            if ($fontFile) {
                $bbox = imagettfbbox($fontSize, 0, $fontFile, 'M');
                $textWidth = abs($bbox[4] - $bbox[0]);
                $textHeight = abs($bbox[5] - $bbox[1]);
                $x = (int)(($size - $textWidth) / 2);
                $y = (int)(($size + $textHeight) / 2);

                imagettftext($image, $fontSize, 0, $x, $y, $white, $fontFile, 'M');
            } else {
                // Fallback: draw "M" using rectangles
                $this->drawLetterM($image, $size, $white);
            }

            $filename = $outputDir . '/icon-' . $size . 'x' . $size . '.png';
            imagepng($image, $filename, 9);
            imagedestroy($image);

            $this->info("Created: icon-{$size}x{$size}.png");
        }

        $this->info('');
        $this->info('PWA icons generated successfully in: public/icons/');

        return self::SUCCESS;
    }

    private function findFont(): ?string
    {
        $fonts = [
            '/usr/share/fonts/truetype/dejavu/DejaVuSans-Bold.ttf',
            '/usr/share/fonts/truetype/liberation/LiberationSans-Bold.ttf',
            '/usr/share/fonts/truetype/freefont/FreeSansBold.ttf',
            'C:/Windows/Fonts/arialbd.ttf',
            'C:/Windows/Fonts/arial.ttf',
        ];

        foreach ($fonts as $font) {
            if (file_exists($font)) {
                return $font;
            }
        }

        return null;
    }

    private function drawLetterM($image, int $size, $color): void
    {
        $margin = (int)($size * 0.2);
        $thick = (int)($size * 0.12);
        $innerMargin = $margin + $thick;

        // Left vertical bar
        imagefilledrectangle($image, $margin, $margin, $innerMargin, $size - $margin, $color);

        // Right vertical bar
        imagefilledrectangle($image, $size - $innerMargin, $margin, $size - $margin, $size - $margin, $color);

        // Top bar
        imagefilledrectangle($image, $margin, $margin, $size - $margin, $margin + $thick, $color);

        // Left diagonal (simplified as rectangle)
        $centerX = (int)($size / 2);
        $midY = (int)($size * 0.5);

        // Draw center V shape using filled polygons
        $points = [
            $innerMargin, $margin + $thick,
            $centerX, $midY,
            $centerX - $thick, $midY,
            $margin, $margin + $thick,
        ];
        imagefilledpolygon($image, $points, 4, $color);

        $points2 = [
            $size - $innerMargin, $margin + $thick,
            $centerX, $midY,
            $centerX + $thick, $midY,
            $size - $margin, $margin + $thick,
        ];
        imagefilledpolygon($image, $points2, 4, $color);
    }
}

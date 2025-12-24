<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;
use Illuminate\Support\Facades\File;

class SystemInfoWidget extends Widget
{
    protected static ?int $sort = 5;

    protected int|string|array $columnSpan = 1;

    protected static string $view = 'filament.widgets.system-info-widget';

    public function getViewData(): array
    {
        $storagePath = storage_path('app/public');
        $storageSize = 0;

        if (File::isDirectory($storagePath)) {
            $storageSize = $this->getDirectorySize($storagePath);
        }

        return [
            'phpVersion' => PHP_VERSION,
            'laravelVersion' => app()->version(),
            'filamentVersion' => \Composer\InstalledVersions::getPrettyVersion('filament/filament') ?? 'N/A',
            'storageUsed' => $this->formatBytes($storageSize),
            'environment' => app()->environment(),
            'debugMode' => config('app.debug') ? 'Wlaczony' : 'Wylaczony',
        ];
    }

    private function getDirectorySize(string $path): int
    {
        $size = 0;
        foreach (File::allFiles($path) as $file) {
            $size += $file->getSize();
        }

        return $size;
    }

    private function formatBytes(int $bytes): string
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $index = 0;

        while ($bytes >= 1024 && $index < count($units) - 1) {
            $bytes /= 1024;
            $index++;
        }

        return round($bytes, 2) . ' ' . $units[$index];
    }
}

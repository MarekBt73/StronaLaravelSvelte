<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;

class QuickLinksWidget extends Widget
{
    protected static ?int $sort = 3;

    protected int|string|array $columnSpan = 1;

    protected static string $view = 'filament.widgets.quick-links-widget';
}

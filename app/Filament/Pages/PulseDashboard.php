<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class PulseDashboard extends Page
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-chart-bar-square';

    protected static ?string $title = 'Monitoring';

    protected static ?string $navigationLabel = 'Monitoring (Pulse)';

    protected static ?int $navigationSort = 99;

    protected static \UnitEnum|string|null $navigationGroup = 'Sistem';

    protected string $view = 'filament.pages.pulse-dashboard';
}

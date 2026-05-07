<?php

namespace App\Filament\Widgets;

use App\Models\Transaction;
use Filament\Widgets\ChartWidget;

class TransactionChartWidget extends ChartWidget
{
    public function getHeading(): ?string
    {
        return 'Pendapatan 7 Hari Terakhir';
    }

    protected static ?int $sort = 2;

    protected ?string $maxHeight = '250px';

    protected function getData(): array
    {
        $data   = [];
        $labels = [];

        foreach (range(6, 0) as $daysAgo) {
            $date     = now()->subDays($daysAgo);
            $labels[] = $date->translatedFormat('D, d M');
            $data[]   = Transaction::whereDate('created_at', $date->toDateString())->sum('total');
        }

        return [
            'datasets' => [
                [
                    'label'           => 'Pendapatan (Rp)',
                    'data'            => $data,
                    'borderColor'     => '#6366f1',
                    'backgroundColor' => 'rgba(99, 102, 241, 0.1)',
                    'fill'            => true,
                    'tension'         => 0.4,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}

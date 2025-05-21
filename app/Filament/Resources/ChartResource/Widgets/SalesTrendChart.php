<?php

namespace App\Filament\Resources\ChartResource\Widgets;

use App\Models\Sale;
use Filament\Widgets\ChartWidget;

class SalesTrendChart extends ChartWidget
{
    protected static ?string $heading = 'اتجاه المبيعات اليومية';
    protected static ?string $description = 'إجمالي المبيعات خلال آخر 30 يومًا';

    protected function getType(): string
    {
        return 'line';
    }

    protected function getData(): array
    {
        // Fetch daily sales totals for the past 30 days
        $sales = Sale::selectRaw('DATE(date_time) as date, SUM(total_price) as total')
            ->whereBetween('date_time', [now()->subDays(30), now()])
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Prepare data for the chart
        $labels = [];
        $data = [];
        $startDate = now()->subDays(30);
        $endDate = now();

        // Initialize data for each day (fill with zeros if no sales)
        for ($date = $startDate->copy(); $date <= $endDate; $date->addDay()) {
            $labels[] = $date->format('Y-m-d');
            $saleForDay = $sales->firstWhere('date', $date->format('Y-m-d'));
            $data[] = $saleForDay ? $saleForDay->total : 0;
        }

        return [
            'datasets' => [
                [
                    'label' => 'المبيعات (د.ع)',
                    'data' => $data,
                    'borderColor' => '#4B5563',
                    'backgroundColor' => 'rgba(75, 85, 99, 0.2)',
                    'fill' => true,
                ],
            ],
            'labels' => $labels,
        ];
    }
}


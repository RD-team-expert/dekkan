<?php

namespace App\Filament\Resources\ChartResource\Widgets;

use App\Models\Sale;
use Filament\Widgets\ChartWidget;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Form;

class SalesTrendChart extends ChartWidget
{
    protected static ?string $heading = 'اتجاه المبيعات اليومية';
    protected static ?string $description = 'إجمالي المبيعات خلال الفترة المحددة';
    
    public ?string $filter = '30';
    public ?string $startDate = null;
    public ?string $endDate = null;

    protected function getType(): string
    {
        return 'line';
    }

    protected function getFilters(): ?array
    {
        return [
            '7' => 'آخر 7 أيام',
            '30' => 'آخر 30 يومًا',
            '90' => 'آخر 90 يومًا',
            'custom' => 'فترة مخصصة',
        ];
    }

    public function getFilterFormSchema(): array
    {
        return [
            DatePicker::make('startDate')
                ->label('تاريخ البداية')
                ->visible(fn () => $this->filter === 'custom')
                ->reactive(),
            DatePicker::make('endDate')
                ->label('تاريخ النهاية')
                ->visible(fn () => $this->filter === 'custom')
                ->reactive(),
        ];
    }

    protected function getData(): array
    {
        // Determine date range based on filter
        if ($this->filter === 'custom' && $this->startDate && $this->endDate) {
            $startDate = \Carbon\Carbon::parse($this->startDate);
            $endDate = \Carbon\Carbon::parse($this->endDate);
        } else {
            $days = (int) $this->filter;
            $startDate = now()->subDays($days);
            $endDate = now();
        }

        // Fetch daily sales totals for the selected period
        $sales = Sale::selectRaw('DATE(date_time) as date, SUM(total_price) as total')
            ->whereBetween('date_time', [$startDate, $endDate])
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Prepare data for the chart
        $labels = [];
        $data = [];

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


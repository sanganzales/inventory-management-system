<?php

namespace App\Filament\Widgets;

use App\Models\Payment;
use Filament\Widgets\LineChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

class PaymentsChart extends LineChartWidget
{
    protected static ?string $heading = 'Chart';
    protected static ?int $sort = 2;

    protected function getFilters(): ?array
    {
        return [
            'today' => 'Today',
            'week' => 'Last week',
            'month' => 'Last month',
            'year' => 'This year',
        ];
    }
    protected function getHeading(): string
    {
        return 'Payments';
    }

    protected function getData(): array
    {
        $activeFilter = $this->filter;
        $data = Trend::query(Payment::where('transactionId','=', 1))
                        ->between(
                            start: now()->startOfYear(),
                            end: now()->endOfYear(),
                        )
                        ->perMonth()
                        ->sum('amount');


        return [
            'datasets' => [
                [
                    'label' => 'Payments created',
                    'data' => $data->map(fn (TrendValue $value) => $value->aggregate),
                ],
            ],
            'labels' => $data->map(fn (TrendValue $value) => $value->date),
        ];
    }
}

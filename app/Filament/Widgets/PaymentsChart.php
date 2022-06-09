<?php

namespace App\Filament\Widgets;

use App\Models\Payment;
use Carbon\Carbon;
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
        $ordersData = Trend::query(Payment::where('transactionId','=', 1))
                        ->between(
                            start: now()->startOfYear(),
                            end: now()->endOfYear(),
                        )
                        ->perMonth()
                        ->sum('amount');

        $purchaseOrdersData = Trend::query(Payment::where('transactionId','=', 2))
                        ->between(
                            start: now()->startOfYear(),
                            end: now()->endOfYear(),
                        )
                        ->perMonth()
                        ->sum('amount');


        return [
            'datasets' => [
                [
                    'label' => 'Sells',
                    'data' => $ordersData->map(fn (TrendValue $value) => $value->aggregate),
                    'borderColor' => 'rgb(128, 255, 0)',
                ],
                [
                    'label' => 'Purchases',
                    'data' => $purchaseOrdersData->map(fn (TrendValue $value) => $value->aggregate),
                    'borderColor' => 'rgb(255, 191, 0)'
                ],
            ],
            'labels' => $ordersData->map(fn (TrendValue $value) => Carbon::createFromFormat('Y-m', $value->date)->format('M')),
        ];
    }
}

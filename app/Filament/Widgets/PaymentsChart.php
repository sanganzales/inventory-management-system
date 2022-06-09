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
    public ?string $filter = 'year';
    protected static ?int $sort = 2;

    protected function getFilters(): ?array
    {
        return [
            'today' => 'Today',
            'week' => 'This week',
            'month' => 'This month',
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

        if($activeFilter=='today')
        {
            return $this->getTodayChart();
        }
        else if($activeFilter=='week')
        {
            return $this->getWeekChart();
        }
        else if($activeFilter=='month')
        {
            return $this->getMonthChart();
        }
        else
        {
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

    //Today Chart
    public function getTodayChart()
    {
        $ordersData = Trend::query(Payment::where('transactionId','=', 1))
                    ->between(
                        start: now()->startOfDay(),
                        end: now()->endOfDay(),
                    )
                    ->perHour()
                    ->sum('amount');

            $purchaseOrdersData = Trend::query(Payment::where('transactionId','=', 2))
                    ->between(
                        start: now()->startOfYear(),
                        end: now()->endOfYear(),
                    )
                    ->perHour()
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
            'labels' => $ordersData->map(fn (TrendValue $value) => Carbon::createFromFormat('Y-m-d H:i', $value->date)->format('H:i')),
            ];


    }

    //This Week Chart
    public function getWeekChart()
    {
                $ordersData = Trend::query(Payment::where('transactionId','=', 1))
                    ->between(
                        start: now()->startOfWeek(),
                        end: now()->endOfWeek(),
                    )
                    ->perDay()
                    ->sum('amount');

            $purchaseOrdersData = Trend::query(Payment::where('transactionId','=', 2))
                    ->between(
                        start: now()->startOfWeek(),
                        end: now()->endOfWeek(),
                    )
                    ->perDay()
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
            'labels' => $ordersData->map(fn (TrendValue $value) => Carbon::createFromFormat('Y-m-d', $value->date)->format('D')), //$value->date
        ];

    }

    //This Month Chart
    public function getMonthChart()
    {
                        $ordersData = Trend::query(Payment::where('transactionId','=', 1))
                    ->between(
                        start: now()->startOfMonth(),
                        end: now()->endOfMonth(),
                    )
                    ->perDay()
                    ->sum('amount');

            $purchaseOrdersData = Trend::query(Payment::where('transactionId','=', 2))
                    ->between(
                        start: now()->startOfMonth(),
                        end: now()->endOfMonth(),
                    )
                    ->perDay()
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
            'labels' => $ordersData->map(fn (TrendValue $value) => Carbon::createFromFormat('Y-m-d', $value->date)->format('d')), //$value->date
        ];

    }
}

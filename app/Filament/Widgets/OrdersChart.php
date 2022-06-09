<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Carbon\Carbon;
use Filament\Widgets\LineChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

class OrdersChart extends LineChartWidget
{
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
        return 'Orders';
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
            $data = Trend::model(Order::class)
            ->between(
                start: now()->startOfYear(),
                end: now()->endOfYear(),
            )
            ->perMonth()
            ->count();
            return [
                'datasets' => [
                    [
                        'label' => 'Blog posts created',
                        'data' => $data->map(fn (TrendValue $value) => $value->aggregate),
                        'borderColor' => 'rgb(0, 191, 255)',
                    ],
                ],
                'labels' => $data->map(fn (TrendValue $value) => Carbon::createFromFormat('Y-m', $value->date)->format('M')), //$value->date
            ];
        }




    }
    //Today Chart
    public function getTodayChart()
    {
        $data = Trend::model(Order::class)
            ->between(
                start: now()->startOfDay(),
                end: now()->endOfDay(),
            )
            ->perHour()
            ->count();
            return [
                'datasets' => [
                    [
                        'label' => 'Blog posts created',
                        'data' => $data->map(fn (TrendValue $value) => $value->aggregate),
                        'borderColor' => 'rgb(0, 191, 255)',
                    ],
                ],
                'labels' => $data->map(fn (TrendValue $value) => Carbon::createFromFormat('Y-m-d H:i', $value->date)->format('H:i')), //$value->date
            ];
    }

    //This Week Chart
    public function getWeekChart()
    {
        $data = Trend::model(Order::class)
            ->between(
                start: now()->startOfWeek(),
                end: now()->endOfWeek(),
            )
            ->perDay()
            ->count();
            return [
                'datasets' => [
                    [
                        'label' => 'Blog posts created',
                        'data' => $data->map(fn (TrendValue $value) => $value->aggregate),
                        'borderColor' => 'rgb(0, 191, 255)',
                    ],
                ],
                'labels' => $data->map(fn (TrendValue $value) => Carbon::createFromFormat('Y-m-d', $value->date)->format('D')), //$value->date
            ];
    }

    //This Month Chart
    public function getMonthChart()
    {
        $data = Trend::model(Order::class)
            ->between(
                start: now()->startOfMonth(),
                end: now()->endOfMonth(),
            )
            ->perDay()
            ->count();
            return [
                'datasets' => [
                    [
                        'label' => 'Blog posts created',
                        'data' => $data->map(fn (TrendValue $value) => $value->aggregate),
                        'borderColor' => 'rgb(0, 191, 255)',
                    ],
                ],
                'labels' => $data->map(fn (TrendValue $value) => Carbon::createFromFormat('Y-m-d', $value->date)->format('d')), //$value->date
            ];
    }

}

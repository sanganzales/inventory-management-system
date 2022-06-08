<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Filament\Widgets\LineChartWidget;
use Illuminate\Support\Facades\DB;

class OrdersChart extends LineChartWidget
{
    protected static ?string $heading = 'Orders per month';

    protected static ?int $sort = 1;

    protected function getData(): array
    {
        $orders = Order::select(DB::raw("(count(id)) as total_orders"),
                                DB::raw("(DATE_FORMAT(created_at, '%M')) as month"),
                                DB::raw("(DATE_FORMAT(created_at, '%m-%Y')) as month_year"))
                        ->orderBy('month_year')
                        ->groupBy(['month','month_year'])
                        ->get();
        foreach($orders as $order)
        {
            $data[] = $order->total_orders;
            $labels[]=$order->month;
        }
        //dd($orders);
        return [
            'datasets' => [
                [
                    'label' => 'Orders',
                    'data' => $data,
                ],
            ],
            'labels' => $labels,
        ];
    }
}

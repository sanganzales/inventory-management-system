<?php

namespace App\Filament\Resources\OrderResource\Widgets;

use App\Models\Order;
use App\Models\Payment;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Card;
use Filament\Widgets\Widget;

class WeeklyOrderOverview extends BaseWidget
{
    //protected static string $view = 'filament.resources.order-resource.widgets.weekly-order-overview';
    protected function getCards(): array
    {
        $user = User::find(auth()->id())->load('userHasCounter');

        if($user->userHasCounter)
        {
            $totalWeeklyOrders =Order::where('counterId',$user->UserHasCounter->counterId)
                                    ->whereBetween('created_at',[now()->startOfWeek(),now()->endOfWeek()])
                                    ->count();
            $totalWeeklyIncomes = Payment::join('orders','orders.id','payments.orderId')
                                    ->where(['transactionId'=>1])
                                    ->where('counterId',$user->UserHasCounter->counterId)
                                    ->whereBetween('payments.created_at',[now()->startOfWeek(),now()->endOfWeek()])
                                    ->sum('payments.amount');
        }
        else
        {
            $totalWeeklyOrders = Order::where('counterId','!=',0)
                    ->whereBetween('created_at',[now()->startOfWeek(),now()->endOfWeek()])
                    ->count();
            $totalWeeklyIncomes = Payment::join('orders','orders.id','payments.orderId')
                    ->where(['transactionId'=>1])
                    ->where('counterId','!=',0)
                    ->whereBetween('payments.created_at',[now()->startOfWeek(),now()->endOfWeek()])
                    ->sum('payments.amount');
        }

        return [
            Card::make('Total Weekly Orders', $totalWeeklyOrders),
            Card::make('Total Weekly Incomes', $totalWeeklyIncomes),
            //Card::make('Average time on page', '3:12'),
        ];
    }
}

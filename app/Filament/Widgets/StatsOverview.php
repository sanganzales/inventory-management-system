<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use App\Models\Payment;
use App\Models\PurchaseOrder;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Card;

class StatsOverview extends BaseWidget
{
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

        $totalWeeklyPuchases = Payment::join('purchase_orders','purchase_orders.id','payments.orderId')
                                    ->where(['transactionId'=>2])
                                    ->whereBetween('payments.created_at',[now()->startOfWeek(),now()->endOfWeek()])
                                    ->sum('payments.amount');

        return [
            Card::make('Weekly Orders', $totalWeeklyOrders),
            Card::make('Weekly Incomes', $totalWeeklyIncomes),
            Card::make('Weekly Expenses', $totalWeeklyPuchases),
        ];
    }
}

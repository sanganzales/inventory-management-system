<?php

namespace App\Filament\Resources\PurchaseOrderResource\Widgets;

use App\Models\Payment;
use App\Models\PurchaseOrder;
use Filament\Widgets\StatsOverviewWidget\Card;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class PurchaseOrderOverview extends BaseWidget
{
    //protected static string $view = 'filament.resources.purchase-order-resource.widgets.purchase-order-overview';

    protected function getCards(): array
    {
        $totalWeeklyPurchases = PurchaseOrder::whereBetween('created_at',[now()->startOfWeek(),now()->endOfWeek()])
                                                ->count();
        $totalWeeklyMovedPurchases = PurchaseOrder::whereBetween('created_at',[now()->startOfWeek(),now()->endOfWeek()])
                                                ->whereNull('warehouseId')
                                                ->count();

        $totalWeeklyPayments = Payment::join('purchase_orders','purchase_orders.id','payments.orderId')
                                                ->where(['transactionId'=>2])
                                                ->whereBetween('payments.created_at',[now()->startOfWeek(),now()->endOfWeek()])
                                                ->sum('payments.amount');


        //dd($numberOfproducts);
        return [
            Card::make('Total Weekly Purchases', $totalWeeklyPurchases),
            Card::make('Total Weekly Payments', $totalWeeklyPayments),
            Card::make('Purchase Not yet Moved', $totalWeeklyMovedPurchases),

        ];
    }
}

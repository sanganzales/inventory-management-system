<?php

namespace App\Filament\Resources\PurchaseOrderResource\Pages;

use App\Filament\Resources\PurchaseOrderResource;
use App\Filament\Resources\PurchaseOrderResource\Widgets\PurchaseOrderOverview;
use Filament\Resources\Pages\ListRecords;

class ListPurchaseOrders extends ListRecords
{
    protected static string $resource = PurchaseOrderResource::class;

    protected function getHeaderWidgets(): array
    {
        return [
                    PurchaseOrderOverview::class
        ];
    }
}

<?php

namespace App\Filament\Resources\WarehouseResource\Widgets;

use App\Models\Stock;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Card;
use Illuminate\Database\Eloquent\Model;

class ProductOverview extends BaseWidget
{
    //protected static string $view = 'filament.resources.warehouse-resource.widgets.product-overview';

    public ?Model $record = null;

    protected function getCards(): array
    {
        $numberOfDifferentProducts = Stock::where(['warehouseId'=>$this->record->id])
                                   ->count();
        $numberOfProducts = Stock::where(['warehouseId'=>$this->record->id])
                            ->sum('quantity');

        //dd($numberOfproducts);
        return [
            Card::make('Number of Products', $numberOfDifferentProducts),
            Card::make('Product Inventory', $numberOfProducts),
            //Card::make('Average time on page', '3:12'),
        ];
    }
}

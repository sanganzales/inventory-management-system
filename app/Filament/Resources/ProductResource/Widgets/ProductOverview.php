<?php

namespace App\Filament\Resources\ProductResource\Widgets;

use App\Models\Product;
use Filament\Widgets\StatsOverviewWidget\Card;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class ProductOverview extends BaseWidget
{
    //protected static string $view = 'filament.resources.product-resource.widgets.product-overview';

    protected function getCards(): array
    {
        $numberOfDifferentProducts = Product::count();


        //dd($numberOfproducts);
        return [
            Card::make('Number of Products', $numberOfDifferentProducts),
            //Card::make('Product Inventory', $numberOfProducts),
            //Card::make('Average time on page', '3:12'),
        ];
    }
}

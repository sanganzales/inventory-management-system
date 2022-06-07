<?php

namespace App\Filament\Resources\WarehouseResource\Pages;

use App\Filament\Resources\WarehouseResource;
use App\Filament\Resources\WarehouseResource\Widgets\ProductOverview;
use Filament\Pages\Actions\Action;
use Filament\Resources\Pages\ViewRecord;

class ViewUser extends ViewRecord
{
    protected static string $resource = WarehouseResource::class;

    protected function getHeaderWidgets(): array
    {
        return [
                ProductOverview::class
        ];
    }

}

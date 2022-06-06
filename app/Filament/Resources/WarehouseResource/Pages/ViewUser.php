<?php

namespace App\Filament\Resources\WarehouseResource\Pages;

use App\Filament\Resources\WarehouseResource;
use Filament\Pages\Actions\Action;
use Filament\Resources\Pages\ViewRecord;

class ViewUser extends ViewRecord
{
    protected static string $resource = WarehouseResource::class;

    protected function getActions(): array
    {
        return array_merge(parent::getActions(),[
            Action::make('Move')
                ->requiresConfirmation(true)
                ->action('move')
        ]);
    }


    public function move()
    {
        dd('yooo');
    }
}

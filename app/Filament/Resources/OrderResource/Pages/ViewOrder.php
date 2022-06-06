<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Filament\Resources\OrderResource;
use Filament\Pages\Actions\Action;
use Filament\Resources\Pages\ViewRecord;

class ViewOrder extends ViewRecord
{
    protected static string $resource = OrderResource::class;
    protected $listeners = ['orderChanged' => '$refresh'];
    protected function getActions(): array
    {
        return array_merge(parent::getActions(), [
            //Action::make('Pay')->action('pay'),
            Action::make('Cancel')->action('cancel'),

        ]);
    }

    public function cancel(): void
    {
        //dd('walayi');
        //$this->getTableRecords()->fresh();
        $this->record->refresh();
    }


    // protected function setUp(): void
    // {
    //     parent::setUp();

    //     $this->registerListeners([
    //         'save' => ['refreshComponent' => '$refresh'],
    //     ]);
    // }
}

<?php

namespace App\Filament\Resources\PurchaseOrderResource\Pages;

use App\Filament\Resources\PurchaseOrderResource;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;
use App\Models\Stock;
use Filament\Forms\Components\BelongsToSelect;
use Filament\Forms\Components\TextInput;
use Filament\Pages\Actions\Action;
use Filament\Resources\Pages\ViewRecord;

class ViewPurchaseOrder extends ViewRecord
{
    protected static string $resource = PurchaseOrderResource::class;

    protected function getActions(): array
    {
        return
                array_merge(parent::getActions(),[
                    Action::make('move')
                            ->label('Move To Store')
                            ->color('warning')
                            ->action('moveToStore')
                            ->requiresConfirmation()
                            ->form([
                                BelongsToSelect::make('warehouseId')->relationship('warehouse','names')
                            ])
                            ->visible(fn() => $order = PurchaseOrder::where('id',$this->record->id)->whereNull('warehouseId')->count())


                ]
                )
        ;
    }


    public function moveToStore($data)
    {
        $purchaseItems = PurchaseOrderItem::where('purchaseOrderId',$this->record->id)->get();
        $stock = Stock::find($data['warehouseId'])->get();
        //Updating Stock Quantities
        foreach($purchaseItems as $purchaseItem)
        {
            $stock = Stock::find($data['warehouseId'])->where('productId',$purchaseItem->productId)->first();
            $stock->quantity = $stock->quantity + $purchaseItem->quantity;
            $stock->save();
            //dd($stock->quantity);
        }
        return redirect()->route('filament.resources.purchase-orders.view',['record'=>$this->record->id]);
    }



}

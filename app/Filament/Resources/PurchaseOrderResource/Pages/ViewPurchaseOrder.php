<?php

namespace App\Filament\Resources\PurchaseOrderResource\Pages;

use App\Filament\Resources\PurchaseOrderResource;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;
use App\Models\Stock;
use App\Models\Warehouse;
use Filament\Forms\Components\BelongsToSelect;
use Filament\Forms\Components\Select;
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
                                Select::make('warehouseId')
                                    ->options(Warehouse::all()->pluck('names','id'))
                                    ->label('Warehouse')
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
        //dd($purchaseItems);
        //Updating Stock Quantities
        foreach($purchaseItems as $purchaseItem)
        {
            //$stock = Stock::find($data['warehouseId'])->where('productId',$purchaseItem->productId)->first();
            $stock = Stock::firstOrCreate([
                'warehouseId'=>$data['warehouseId'],
                'productId'=>$purchaseItem->productId
            ],[
                'quantity'=>$purchaseItem->quantity,
                'createdBy'=>auth()->id()
            ]);
            //dd($stock);
            if(!$stock->wasRecentlyCreated)
            {
                $stock->quantity = $stock->quantity + $purchaseItem->quantity;
                $stock->save();
            }
        }
        //ToDO: update warehouse for purchase order and manually redirect as refresh
        $this->record->warehouseId = $data['warehouseId'];
        $this->record->save();
        return redirect()->route('filament.resources.purchase-orders.view',['record'=>$this->record->id]);
    }



}

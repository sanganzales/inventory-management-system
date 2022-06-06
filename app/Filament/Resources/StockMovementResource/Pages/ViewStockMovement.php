<?php

namespace App\Filament\Resources\StockMovementResource\Pages;

use App\Filament\Resources\StockMovementResource;
use App\Models\Stock;
use App\Models\StockMovementDetail;
use App\Models\Warehouse;
use Filament\Forms\Components\Select;
use Filament\Pages\Actions\Action;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ViewStockMovement extends ViewRecord
{
    protected static string $resource = StockMovementResource::class;

    protected function getActions(): array
    {
        return array_merge(parent::getActions(),
        [
            Action::make('move')
                ->requiresConfirmation()
                ->action('move')
                ->label('Move')
                ->form([
                    Select::make('warehouseId')
                        ->label('Warehouse')
                        ->options(Warehouse::whereNot('id',$this->record->sourceWarehouseId)->pluck('names','id'))
                ])
                ->visible(fn()=> !$this->record->isDone)

        ]);
    }

    public function move($data)
    {
        if($this->checkQuantityInStock())
        {
            $details = StockMovementDetail::where(['stockMovementId'=>$this->record->id])
                            ->get();

            foreach($details as $detail)
            {
                $stock = Stock::where(['warehouseId'=>$this->record->sourceWarehouseId])
                            ->where(['productId'=>$detail->productId])
                            ->first();
                $stock->quantity = $stock->quantity - $detail->quantity;
                $stock->save();

                //Create new or update
                $newStock = Stock::firstOrCreate([
                        'warehouseId' =>$data['warehouseId']
                    ],
                    [
                        'productId' => $detail->productId,
                        'quantity' => $detail->quantity,
                        'createdBy' => auth()->id()
                ]);

                if(!$newStock->wasRecentlyCreated)
                {
                    $newStock->quantity = $newStock->quantity + $detail->quantity;
                    $newStock->save();
                }

            }

            $this->record->destinationWarehouseId = $data['warehouseId'];
            $this->record->isDone = true;
            $this->record->save();
            //dd($this->record->id);

            $this->notify('message','Stock Qantity was successfuly moved');


        }
        else
        $this->notify('warning','One of more stock quantity has been update, please Review Stocks');
        return redirect()->route('filament.resources.stock-movements.view',['record'=>$this->record->id]);

    }

    public function checkQuantityInStock():bool
    {
        $status = true;
        $details = StockMovementDetail::where(['stockMovementId'=>$this->record->id])
                            ->get();
        foreach($details as $detail)
        {
            $isQuantityEnough = Stock::where(['warehouseId'=>$this->record->sourceWarehouseId])
                        ->where(['productId'=>$detail->productId])
                        ->where('quantity','>=',$detail->quantity)
                        ->count();
            if($isQuantityEnough==0)
            {
                $status=false;
            }
        }
        return $status;
    }
}

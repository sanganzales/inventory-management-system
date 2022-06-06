<?php

namespace App\Filament\Resources\PurchaseOrderResource\RelationManagers;

use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;
use Closure;
use Filament\Forms;
use Filament\Forms\Components\BelongsToSelect;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TextInput\Mask;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\HasManyRelationManager;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class PurchaseItemsRelationManager extends HasManyRelationManager
{
    protected static string $relationship = 'PurchaseItems';

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                BelongsToSelect::make('productId')
                    ->required()
                    ->relationship('product','name'),
                TextInput::make('price')
                    ->required()
                    ->reactive()
                    ->mask(fn(Mask $mask) => $mask
                                ->thousandsSeparator(',')
                                ->decimalSeparator('.')
                                ->numeric()
                                ->minValue(0))
                    ->afterStateUpdated(function(Closure $get, Callable $set){

                        $get('quantity')!==null?$quantity = $get('quantity'):$quantity =0;
                        $get('price')!==null?$price = $get('price'):$price = 0;

                        $set('total',(int)$quantity*(int)$price);
                    } ),
                TextInput::make('quantity')
                    ->required()
                    ->numeric()
                    ->minValue(1)
                    ->reactive()
                    ->afterStateUpdated(function(Closure $get, Callable $set){

                        $get('quantity')!==null?$quantity = $get('quantity'):$quantity = 0;
                        $get('price')!==null?$price = $get('price'):$price = 0;

                        $set('total',(int)$quantity*(int)$price);
                    } ),
                TextInput::make('total')
                    ->required()
                    ->disabled(true)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('product.name'),
                TextColumn::make('price'),
                TextColumn::make('quantity'),
                TextColumn::make('total'),

            ])
            ->filters([
                //
            ]);
    }

    protected function afterCreate()
    {

        $totalOrdered = PurchaseOrderItem::join('purchase_orders','purchase_order_items.purchaseOrderId','purchase_orders.id')
                            ->where('purchaseOrderId',$this->ownerRecord->id)
                            ->sum(DB::raw('( price* quantity)'));
        $this->ownerRecord->amount=$totalOrdered;
        $this->ownerRecord->save();
        $this->ownerRecord->refresh();

        return redirect()->route('filament.resources.purchase-orders.view',['record'=>$this->ownerRecord->id]);

    }

    protected function afterDelete()
    {

        $totalOrdered = PurchaseOrderItem::join('purchase_orders','purchase_order_items.purchaseOrderId','purchase_orders.id')
                            ->where('purchaseOrderId',$this->ownerRecord->id)
                            ->sum(DB::raw('( price* quantity)'));
        $this->ownerRecord->amount=$totalOrdered;
        $this->ownerRecord->save();
        $this->ownerRecord->refresh();

        return redirect()->route('filament.resources.purchase-orders.view',['record'=>$this->ownerRecord->id]);

    }
    protected function canCreate(): bool
    {
        $isPayed = PurchaseOrder::all()->where('id',$this->ownerRecord->id)->pluck('purchaseStatusId')->first();

        return $isPayed === 3? false:true;

    }

    protected function canEdit(Model $record): bool
    {
        $isPayed = PurchaseOrder::all()->where('id',$this->ownerRecord->id)->pluck('purchaseStatusId')->first();

        return $isPayed === 3? false:true;
    }

    protected function canDelete(Model $record): bool
    {
        $isPayed = PurchaseOrder::all()->where('id',$this->ownerRecord->id)->pluck('purchaseStatusId')->first();

        return $isPayed === 3? false:true;
    }

}

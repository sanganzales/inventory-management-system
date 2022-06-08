<?php

namespace App\Filament\Resources\OrderResource\RelationManagers;

use App\Filament\Resources\OrderItemResource;
use App\Models\Item;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Stock;
use App\Models\User;
use Closure;
use Filament\Forms;
use Filament\Forms\Components\BelongsToSelect;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\HasManyRelationManager;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class OrderItemsRelationManager extends HasManyRelationManager
{
    protected static string $relationship = 'orderItems';

    protected static ?string $recordTitleAttribute = 'items';

    public static function form(Form $form): Form
    {
        return $form
            ->schema(
                [
                    Select::make('productId')->options(Product::all()->pluck('name','id'))
                                             ->required()
                                             ->searchable(),
                    TextInput::make('quantity')->required()
                                               ->numeric()
                                               ->minValue(1)
                                               ->rules([function(Closure $get,$livewire){
                                                    return function (string $attribute,$value,Closure $fail) use($get,$livewire){
                                                        $user = User::find(auth()->id());
                                                        $isItemStillInStock = Stock::where('productId',$get('productId'))
                                                                                            ->where('stocks.quantity','>=',$value)
                                                                                            ->where('warehouseId',$livewire->ownerRecord->counterId)
                                                                                            ->count('stocks.id');
                                                        if($isItemStillInStock==0) $fail('Quantity is greater than in our stocks ');
                                                    };
                                                }])

                ]
            );
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns(
                [
                    TextColumn::make('product.name'),
                    TextColumn::make('product.price')->label('Price')->money('rwf'),
                    TextColumn::make('quantity'),
                    TextColumn::make('subTotal')->money('rwf'),

                    ]
            )
            ->filters([
                //
            ]);
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['createdBy'] = auth()->id();

        return $data;

    }
    protected function canCreate(): bool
    {
        $isPayed = Order::all()->where('id',$this->ownerRecord->id)->pluck('statusId')->first();

        return $isPayed === 3? false:true;

    }

    protected function canDelete(Model $record): bool
    {
        $isPayed = Order::all()->where('id',$this->ownerRecord->id)->pluck('statusId')->first();

        return $isPayed === 3? false:true;
    }

    protected function canEdit(Model $record): bool
    {
        $isPayed = Order::all()->where('id',$this->ownerRecord->id)->pluck('statusId')->first();

        return $isPayed === 3? false:true;
    }

    protected function afterCreate()
    {

        $totalOrdered = OrderItem::join('products','order_items.productId','products.id')
                            ->where('orderId',$this->ownerRecord->id)
                            ->sum(DB::raw('price* quantity'));
        $this->ownerRecord->amount=$totalOrdered;
        $this->ownerRecord->statusId=2;
        $this->ownerRecord->save();
        return redirect()->route('filament.resources.orders.view',['record'=>$this->ownerRecord->id]);


    }

}

<?php

namespace App\Filament\Resources\OrderResource\RelationManagers;

use App\Filament\Resources\PaymentResource;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\PaymentMode;
use App\Models\Stock;
use Closure;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TextInput\Mask;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\HasManyRelationManager;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

use function PHPUnit\Framework\throwException;

class PaymentsRelationManager extends HasManyRelationManager
{
    protected static string $relationship = 'payments';

    protected static ?string $recordTitleAttribute = 'payments';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('paymentModeId')->options(PaymentMode::all()->pluck('name','id'))
                                     ->required()
                                     ->searchable(),
                TextInput::make('amount')->required()
                                    ->mask(fn(Mask $mask) => $mask
                                            ->thousandsSeparator(',')
                                            ->decimalSeparator('.')
                                            ->numeric()

                                          )
                                    ->rules([
                                        function($livewire){
                                            return function (string $attribute,$value,Closure $fail)use($livewire){

                                                $totalAlreadyPaid = Payment::where('orderId',$livewire->ownerRecord->id)
                                                                            ->where('transactionId',1)->sum('amount');
                                                $totalOrdered = OrderItem::join('items','order_items.itemId','items.id')
                                                                    ->where('orderId',$livewire->ownerRecord->id)
                                                                    ->sum(DB::raw('( price* quantity) - discount'));

                                                if($value > ($totalOrdered-$totalAlreadyPaid))
                                                    $fail('Amount entered is greater than the remaining balance');

                                            };
                                        }
                                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns(
                [
                    TextColumn::make('amount')->money('rwf'),
                    TextColumn::make('paymentModes.name')->label('Payment Mode'),
                    //TextColumn::make('quantity'),
                    //TextColumn::make('subTotal')->money('rwf'),

                    ]
            )
            ->filters([
                //
            ]);
    }

    protected function getTableQuery(): Builder
    {
        return Payment::where('transactionId',1)->where('orderId',$this->ownerRecord->id);
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['createdBy'] = auth()->id();
        $data['transactionId'] = 1; //For Sales Transaction

        return $data;

    }

    protected function canCreate(): bool
    {
        $isPayed = Order::all()->where('id',$this->ownerRecord->id)->pluck('statusId')->first();

        return $isPayed === 3? false:true;

    }

    protected function afterCreate()
    {
        $totalAlreadyPaid = Payment::where('orderId',$this->ownerRecord->id)
                                    ->where('transactionId',1)->sum('amount');
        $totalOrdered = OrderItem::join('items','order_items.itemId','items.id')
                            ->where('orderId',$this->ownerRecord->id)
                            ->sum(DB::raw('( price* quantity) - discount'));
        if($totalAlreadyPaid === $totalOrdered)
        {
            //changing order status to completed
            $order = Order::find($this->ownerRecord->id);
            $order->statusId =3;
            $order->save();


            //updating stock quantity
            $orderItems = OrderItem::join('items','order_items.itemId','items.id')
                                        ->where('orderId',$this->ownerRecord->id)
                                        ->get();
            foreach ($orderItems as $orderItem) {

                    $stock = Stock::where('productId',$orderItem->item->productId)
                                ->where('warehouseId',$this->ownerRecord->counter->warehouseId)
                                ->first();
                    $stock->quantity = $stock->quantity - $orderItem->quantity;
                    $stock->save();

            }
            return redirect()->route('filament.resources.orders.view',['record'=>$this->ownerRecord->id]);



        }


    }

}

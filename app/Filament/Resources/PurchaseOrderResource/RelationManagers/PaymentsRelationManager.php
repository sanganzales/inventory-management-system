<?php

namespace App\Filament\Resources\PurchaseOrderResource\RelationManagers;

use App\Models\Payment;
use App\Models\PaymentMode;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;
use Closure;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TextInput\Mask;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\HasManyRelationManager;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class PaymentsRelationManager extends HasManyRelationManager
{
    protected static string $relationship = 'payments';

    protected static ?string $recordTitleAttribute = 'title';

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
                                                                                ->where('transactionId',2)->sum('amount');
                                                $totalOrdered = PurchaseOrderItem::join('purchase_orders','purchase_order_items.purchaseOrderId','purchase_orders.id')
                                                                    ->where('purchase_orders.id',$livewire->ownerRecord->id)
                                                                    ->sum(DB::raw('( price* quantity)'));

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
        return Payment::where('transactionId',2)->where('orderId',$this->ownerRecord->id);
    }
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['createdBy'] = auth()->id();
        $data['transactionId'] = 2; //For Purchases Transaction

        return $data;

    }

    protected function afterCreate()
    {
        $totalAlreadyPaid = Payment::where('orderId',$this->ownerRecord->id)
                                        ->where('transactionId',2)->sum('amount');
        $totalOrdered = PurchaseOrderItem::join('purchase_orders','purchase_order_items.purchaseOrderId','purchase_orders.id')
                                            ->where('purchase_orders.id',$this->ownerRecord->id)
                                            ->sum(DB::raw('( price* quantity)'));
        if($totalAlreadyPaid === $totalOrdered)
        {
            //changing order status to completed
            $order = PurchaseOrder::find($this->ownerRecord->id);
            $order->purchaseStatusId =3;
            $order->save();

            return redirect()->route('filament.resources.purchase-orders.view',['record'=>$this->ownerRecord->id]);



        }
    }

    protected function canCreate(): bool
    {
        $isPayed = PurchaseOrder::all()->where('id',$this->ownerRecord->id)->pluck('purchaseStatusId')->first();
        $anyPurchaseOrderItem = PurchaseOrderItem::where('purchaseOrderId',$this->ownerRecord->id)->count();

        return ($isPayed === 3 | $anyPurchaseOrderItem==0)? false:true;

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

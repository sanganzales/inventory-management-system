<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Filament\Resources\OrderResource\Pages\ViewOrder;
use App\Filament\Resources\OrderResource\RelationManagers;
use App\Filament\Resources\OrderResource\RelationManagers\OrderItemsRelationManager;
use App\Filament\Resources\OrderResource\RelationManagers\PaymentsRelationManager;
use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Status;
use App\Models\User;
use Closure;
use Filament\Forms;
use Filament\Forms\Components\BelongsToSelect;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Pages\Page;
use Filament\Resources\Form;
use Filament\Resources\Pages\CreateRecord;
use Filament\Resources\Pages\EditRecord;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';
    protected static ?string $navigationGroup = 'Sales Management';
    protected static ?int $navigationSort = 3;




    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                BelongsToSelect::make('customerId')->relationship('customer', 'names')
                                                   ->createOptionForm([
                                                    TextInput::make('names')->required(),
                                                    TextInput::make('email')->required()
                                                                            ->email(),
                                                    TextInput::make('phone')->required()
                                                                            ->tel(),
                                                    Hidden::make('createdBy')->required()->default(auth()->id())
                                                    ])
                                                    ->searchable(),
                Select::make('statusId')->options(Status::all()->pluck('name','id'))
                                        ->disabled(false)
                                        ->dehydrated(false)
                                        ->visible(fn (Page $livewire) => !$livewire instanceof CreateRecord)
                                        ->label('Status'),
                TextInput::make('amount')->label('Total Amount')
                                         ->visible(fn($livewire) => $livewire instanceof ViewOrder),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->sortable(),
                TextColumn::make('orderReferenceNumber')->label('Order Reference Number'),
                TextColumn::make('Customer.names')->label('Customer Names'),
                BadgeColumn::make('status.name')->label('Status')
                                                  ->colors([
                                                      'danger'=>'Cancelled',
                                                      'warning'=>'Processing',
                                                      'success'=>'Payed'
                                                  ]),
                TextColumn::make('created_at')->label('Created At')->dateTime(),

            ])
            ->filters([
                SelectFilter::make('statusId')->options([
                    '1' => 'Processing',
                    '2' => 'Cancelled',
                    '3' => 'Paid'
                ])->label('Status')
                ])
                ->defaultSort('id','DESC')
                ;

    }


    public static function getRelations(): array
    {
        return [
            OrderItemsRelationManager::class,
            PaymentsRelationManager::class

        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'view' => Pages\ViewOrder::route('/{record}'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['createdBy'] = auth()->id();
        return $data;

    }

}

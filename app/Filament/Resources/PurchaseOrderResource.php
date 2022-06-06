<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PurchaseOrderResource\Pages;
use App\Filament\Resources\PurchaseOrderResource\Pages\CreatePurchaseOrder;
use App\Filament\Resources\PurchaseOrderResource\Pages\EditPurchaseOrder;
use App\Filament\Resources\PurchaseOrderResource\Pages\ViewPurchaseOrder;
use App\Filament\Resources\PurchaseOrderResource\RelationManagers;
use App\Filament\Resources\PurchaseOrderResource\RelationManagers\PaymentsRelationManager;
use App\Filament\Resources\PurchaseOrderResource\RelationManagers\PurchaseItemsRelationManager;
use App\Models\PurchaseOrder;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Components\BelongsToSelect;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Model;

class PurchaseOrderResource extends Resource
{
    protected static ?string $model = PurchaseOrder::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    protected static ?string $navigationGroup = 'Accounting Management';
    protected static ?int $navigationSort = 1;


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('purchaseNumber')
                    ->required()
                    ->default(fn()=> Carbon::now()->timestamp)
                    ->disabled(),
                BelongsToSelect::make('supplierId')->relationship('supplier','names')
                                                   ->searchable()
                                                   ->label('Supplier'),
                TextInput::make('amount')->visible(fn($livewire) => !$livewire instanceof CreatePurchaseOrder),
                BelongsToSelect::make('warehouseId')
                                ->relationship('warehouse','names')
                                ->visible(fn($livewire) => !$livewire instanceof CreatePurchaseOrder),
                BelongsToSelect::make('purchaseStatusId')
                                ->relationship('status','name')
                                ->visible(fn($livewire) => $livewire instanceof ViewPurchaseOrder),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('purchaseNumber'),
                //Tables\Columns\TextColumn::make('createdBy'),
                Tables\Columns\TextColumn::make('amount'),
                Tables\Columns\TextColumn::make('status.name'),
                Tables\Columns\TextColumn::make('warehouse.names'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime(),
                // Tables\Columns\TextColumn::make('updated_at')
                //     ->dateTime(),
            ])
            ->filters([
                //
            ]);
    }

    public static function getRelations(): array
    {
        return [
            PurchaseItemsRelationManager::class,
            PaymentsRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPurchaseOrders::route('/'),
            'create' => Pages\CreatePurchaseOrder::route('/create'),
            'edit' => Pages\EditPurchaseOrder::route('/{record}/edit'),
            'view' => Pages\ViewPurchaseOrder::route('/{record}'),
        ];
    }

    public static function canEdit(Model $record):bool
    {
            return PurchaseOrder::where('id',$record->id)->whereNull('warehouseId')->count();
    }

}

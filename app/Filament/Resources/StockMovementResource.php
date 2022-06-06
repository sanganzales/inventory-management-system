<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StockMovementResource\Pages;
use App\Filament\Resources\StockMovementResource\Pages\ViewStockMovement;
use App\Filament\Resources\StockMovementResource\RelationManagers;
use App\Filament\Resources\StockMovementResource\RelationManagers\DetailsRelationManager;
use App\Models\StockMovement;
use Filament\Forms;
use Filament\Forms\Components\BelongsToSelect;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;

class StockMovementResource extends Resource
{
    protected static ?string $model = StockMovement::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';
    protected static ?string $navigationGroup = 'Inventory Management';
    protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                BelongsToSelect::make('sourceWarehouseId')->relationship('source','names')
                    ->required(),
                BelongsToSelect::make('destinationWarehouseId')->relationship('destination','names')
                    ->visible(fn($livewire) => $livewire instanceof ViewStockMovement),
                Toggle::make('isDone')
                    ->label('Is it Done?')
                    ->visible(fn($livewire) => $livewire instanceof ViewStockMovement),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('source.names'),
                Tables\Columns\TextColumn::make('destination.names')->label('Destination'),
                Tables\Columns\BooleanColumn::make('isDone'),
                Tables\Columns\TextColumn::make('user.name'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime(),
            ])
            ->filters([
                //
            ]);
    }

    public static function getRelations(): array
    {
        return [
            DetailsRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListStockMovements::route('/'),
            'create' => Pages\CreateStockMovement::route('/create'),
            'view' => Pages\ViewStockMovement::route('/{record}'),
            'edit' => Pages\EditStockMovement::route('/{record}/edit'),
        ];
    }
}

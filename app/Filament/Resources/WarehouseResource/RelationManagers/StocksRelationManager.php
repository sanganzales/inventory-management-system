<?php

namespace App\Filament\Resources\WarehouseResource\RelationManagers;

use App\Models\Product;
use App\Models\Supplier;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\HasManyRelationManager;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;

class StocksRelationManager extends HasManyRelationManager
{
    protected static string $relationship = 'stocks';

    protected static ?string $recordTitleAttribute = 'name';



    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('productId')->options(Product::all()->pluck('name','id'))
                                          ->label('Product Name')
                                          ->searchable(),
                TextInput::make('quantity')->required(),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('product.name')->label('Product Name'),
                TextColumn::make('quantity')->label('Quantity'),
            ])
            ->filters([
                //
            ]);
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['createdBy'] = auth()->id();

        return $data;

    }
}

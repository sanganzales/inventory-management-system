<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ItemResource\Pages;
use App\Filament\Resources\ItemResource\RelationManagers;
use App\Models\Item;
use App\Models\Product;
use Faker\Provider\ar_EG\Text;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;

class ItemResource extends Resource
{
    protected static ?string $model = Item::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';
    protected static ?string $navigationGroup = 'Sales Management';
    protected static ?int $navigationSort = 2;


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('productId')->options(Product::all()->pluck('name','id'))
                                         ->searchable(),
                TextInput::make('price')->required()
                                        ->label('Price(RwF)')
                                        ->numeric()
                                        ->mask(fn (TextInput\Mask $mask) => $mask
                                            ->numeric()
                                            ->decimalPlaces(2) // Set the number of digits after the decimal point.
                                            ->decimalSeparator('.') // Add a separator for decimal numbers.
                                            ->mapToDecimalSeparator([',']) // Map additional characters to the decimal separator.
                                            ->normalizeZeros() // Append or remove zeros at the end of the number.
                                            ->padFractionalZeros() // Pad zeros at the end of the number to always maintain the maximum number of decimal places.
                                            ->thousandsSeparator(','), // Add a separator for thousands.
                                        ),
                TextInput::make('discount')->required()->label('Discount(RwF)')
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('product.name')->searchable()->sortable(),
                TextColumn::make('price'),
                TextColumn::make('discount')
            ])
            ->filters([
                //
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListItems::route('/'),
            'create' => Pages\CreateItem::route('/create'),
            'edit' => Pages\EditItem::route('/{record}/edit'),
        ];
    }
}

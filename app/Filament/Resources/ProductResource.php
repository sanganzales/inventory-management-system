<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Filament\Resources\ProductResource\RelationManagers;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Closure;
use Filament\Forms;
use Filament\Forms\Components\BelongsToManyMultiSelect;
use Filament\Forms\Components\BelongsToSelect;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';
    protected static ?string $navigationGroup = 'Inventory Management';
    protected static ?int $navigationSort = 4;


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Fieldset::make('Description')->schema([
                    TextInput::make('name')->required(),
                    BelongsToSelect::make('brandId')->relationship('brand','name')
                                                    ->searchable(),
                    BelongsToManyMultiSelect::make('categories')->relationship('categories','name'),
                    TextInput::make('barcode'),
                    MarkdownEditor::make('description')
                            ->columnSpan([
                                'sm' => 2,
                            ]),
                ]),
                Fieldset::make('Pricing & Inventory')->schema([
                    TextInput::make('price')->numeric()
                                            ->required(),
                TextInput::make('security_stock')
                                            ->helperText('The safety stock is the limit stock for your products which alerts you if the product stock will soon be out of stock.')
                                            ->numeric()
                                            ->rules(['integer', 'min:0'])
                                            ->required(),

                ]),




            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->label('Names'),
                TextColumn::make('brand.name')->label('Brand'),
                TextColumn::make('price')->label('Price'),
                //TextColumn::make('content')->label('Content'),

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
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'view' => Pages\ViewProduct::route('/{record}'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}

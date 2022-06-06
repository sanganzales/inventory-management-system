<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Filament\Resources\ProductResource\RelationManagers;
use App\Models\Category;
use App\Models\Product;
use Closure;
use Filament\Forms;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;

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
                Fieldset::make('Names')->schema([
                    TextInput::make('name')->required(),
                ]),
                Fieldset::make('Category')->schema([
                    Select::make('categoryId')->options(Category::where('categoryId',null)->orderBy('name')->pluck('name','id'))
                                          ->label('Category')
                                          ->searchable()
                                          ->reactive()
                                          ,
                Select::make('subCategoryId')->options(function(Closure $get)
                {
                    return Category::where('categoryId',$get('categoryId'))->orderBy('name')->pluck('name','id');
                }
                                                        )
                                            ->visible(fn(Closure $get) => $get('categoryId')!=null)
                                            ->searchable()
                                            ->label('Sub Category')
                                            ->required(fn(Closure $get) => $get('categoryId')!=null),
                ]),

                Fieldset::make('Description')->schema([

                Textarea::make('summary')->required(),
                RichEditor::make('content')->required()->disableAllToolbarButtons()
                                           ->enableToolbarButtons(['bold','italic',]),
                ]),




            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->label('Names'),
                TextColumn::make('category.name')->label('Category'),
                TextColumn::make('subCategory.name')->label('Sub-Category'),
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

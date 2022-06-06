<?php

namespace App\Filament\Resources\StockMovementResource\RelationManagers;

use App\Models\Product;
use App\Models\Stock;
use App\Models\User;
use Closure;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\HasManyRelationManager;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Support\Facades\Auth;
use Livewire\Livewire;

class DetailsRelationManager extends HasManyRelationManager
{
    protected static string $relationship = 'details';

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('productId')
                    ->label('Product')
                    ->options(Product::all()->pluck('name','id'))
                    ->searchable()
                    ->required(),
                TextInput::make('quantity')
                    ->label('Qantity')
                    ->required()
                    ->numeric()
                    ->rules([
                        function($livewire,Closure $get){
                            return function(string $attribute,$value,Closure $fail)use($livewire,$get)
                            {
                                $user = User::find(Auth()->id());
                                $stock = Stock::where([
                                                                    'warehouseId'=> $livewire->ownerRecord->sourceWarehouseId
                                                                ])
                                                            ->where('productId',$get('productId'))
                                                            ->first();
                                if($stock->quantity<$value)
                                $fail('Quantity is greater than in our stocks');
                            };
                        }
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('product.name'),
                TextColumn::make('quantity')
            ])
            ->filters([
                //
            ]);
    }
}

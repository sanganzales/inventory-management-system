<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\Role;
use App\Models\User;
use Closure;
use Filament\Forms;
use Filament\Forms\Components\BelongsToManyMultiSelect;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Model;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    protected static ?string $navigationGroup = 'Settings';
    protected static ?int $navigationSort = 1;



    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')->required()->label('Full Names'),
                TextInput::make('email')->required(),
                BelongsToManyMultiSelect::make('roles')->relationship('roles', 'name'),
                //Select::make('roleId')->options(Role::all()->pluck('name','id'))
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->label('Full Names')->sortable(),
                TextColumn::make('email'),
                TextColumn::make('roles.name')->label('Role'),
                TextColumn::make('userHasCounter.counter.name')->label('Counter Name')
            ])
            ->filters([
                //
            ])
            // ->pushActions([
            //     Action::make('assign')
            //     ->label('Assign Counter')
            //     ->color('warning')
            //     ->url(fn (User $record): string => route('filament.resources.user-resource.edit', $record))
            // ])
           ;
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'assign' => Pages\AssignUsers::route('/{record}/assign'),
            'view' => Pages\ViewUser::route('/{record}'),
            'edit' => Pages\EditUser::route('/{record}/edit'),

        ];
    }
}

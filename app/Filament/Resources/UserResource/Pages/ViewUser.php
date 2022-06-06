<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use App\Models\Counter;
use App\Models\UserHasCounter;
use Filament\Forms\Components\BelongsToSelect;
use Filament\Forms\Components\Select;
use Filament\Pages\Actions\Action;
use Filament\Resources\Pages\ViewRecord;

class ViewUser extends ViewRecord
{
    protected static string $resource = UserResource::class;

    protected function getActions(): array
    {
        return array_merge(parent::getActions(),[
            Action::make('assign')
                ->label('Assign Counter')
                ->action('assignCounter')
                ->requiresConfirmation()
                ->form([
                    Select::make('counterId')
                        ->options(Counter::all()->pluck('name','id'))
                        ->searchable()
                ])
                ->visible(fn()=> $this->record->roles()->first()->id==4 )
        ]);
    }

    public function assignCounter($data)
    {
        UserHasCounter::firstOrCreate([
            'userId' => $this->record->id,
            'counterId' => $data['counterId']
        ]);

    }
}

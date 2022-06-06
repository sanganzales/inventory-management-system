<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Filament\Resources\OrderResource;
use App\Models\User;
use Filament\Resources\Pages\CreateRecord;

class CreateOrder extends CreateRecord
{

    protected static string $resource = OrderResource::class;
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $user = User::find(auth()->id());
        $data['createdBy'] = auth()->id();

        $current_time = \Carbon\Carbon::now()->timestamp;
        $data['orderReferenceNumber'] = 'ORN'.$current_time;

        //dd($user->UserHasCounter->counterId);
        $data['counterId'] = $user->UserHasCounter->counterId;


        return $data;

    }
    protected function afterCreate(): void
    {
        // Runs after the form fields are saved to the database.
    }
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('view',['record'=>$this->record->id]);
    }

}

<?php

namespace App\Filament\Resources\PaymentModeResource\Pages;

use App\Filament\Resources\PaymentModeResource;
use Filament\Resources\Pages\ManageRecords;

class ManagePaymentModes extends ManageRecords
{
    protected static string $resource = PaymentModeResource::class;
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['createdBy'] = auth()->id();

        return $data;

    }
}


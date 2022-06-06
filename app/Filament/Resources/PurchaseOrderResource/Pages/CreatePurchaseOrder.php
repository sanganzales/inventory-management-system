<?php

namespace App\Filament\Resources\PurchaseOrderResource\Pages;

use App\Filament\Resources\PurchaseOrderResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePurchaseOrder extends CreateRecord
{
    protected static string $resource = PurchaseOrderResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['createdBy'] = auth()->id();
        $data['purchaseStatusId'] = 1;
        $data['amount'] = 0;


        return $data;

    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('view',['record'=>$this->record->id]);
    }
}

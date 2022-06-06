<?php

namespace App\Filament\Resources\CounterResource\Pages;

use App\Filament\Resources\CounterResource;
use Filament\Resources\Pages\ManageRecords;

class ManageCounters extends ManageRecords
{
    protected static string $resource = CounterResource::class;
}

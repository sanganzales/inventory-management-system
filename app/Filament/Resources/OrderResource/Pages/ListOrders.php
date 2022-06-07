<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Filament\Resources\OrderResource;
use App\Filament\Resources\OrderResource\Widgets\WeeklyOrderOverview;
use App\Models\Order;
use App\Models\User;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListOrders extends ListRecords
{
    //protected $listeners = ['refreshComponent' => '$refresh'];

    protected static string $resource = OrderResource::class;

    protected function getTableQuery(): Builder
    {
        $user = User::find(auth()->id())->load(['userHasCounter']);
        if($user->userHasCounter)
        return Order::where('counterId',$user->UserHasCounter->counterId);
        else
        return Order::where('counterId','!=',0);

    }


    protected function getHeaderWidgets(): array
    {
        return [
                    WeeklyOrderOverview::class
        ];
    }

}

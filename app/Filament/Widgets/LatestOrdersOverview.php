<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\OrderResource;
use Filament\Widgets\Widget;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;
class LatestOrdersOverview extends BaseWidget
{

    // protected int | string | array $columnSpan = 'full';

    // protected static ?int $sort = 2;

    // public function getDefaultTableRecordsPerPageSelectOption(): int
    // {
    //     return 5;
    // }

    // protected function getDefaultTableSortColumn(): ?string
    // {
    //     return 'created_at';
    // }

    // protected function getDefaultTableSortDirection(): ?string
    // {
    //     return 'desc';
    // }

    // protected function getTableQuery(): Builder
    // {
    //     return OrderResource::getEloquentQuery();
    // }
    // protected function getTableColumns(): array
    // {
    //     return [
    //        TextColumn::make('created_at')
    //                 ->label('Order Date')
    //                 ->date()
    //                 ->sortable(),
    //         Tables\Columns\TextColumn::make('customer.names')
    //             ->searchable()
    //             ->sortable(),
    //         Tables\Columns\BadgeColumn::make('status.name')
    //             ->colors([
    //                 'secondary',
    //                 'danger' => 'cancelled',
    //                 'warning' => 'Processing',
    //                 'success' => 'Paid',
    //             ]),
    //         Tables\Columns\TextColumn::make('amount')
    //             ->searchable()
    //             ->sortable(),

    //     ];
    // }
}

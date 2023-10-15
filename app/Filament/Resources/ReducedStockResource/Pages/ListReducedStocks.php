<?php

namespace App\Filament\Resources\ReducedStockResource\Pages;

use App\Filament\Resources\ReducedStockResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListReducedStocks extends ListRecords
{
    protected static string $resource = ReducedStockResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}

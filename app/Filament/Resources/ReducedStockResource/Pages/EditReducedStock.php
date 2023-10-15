<?php

namespace App\Filament\Resources\ReducedStockResource\Pages;

use App\Filament\Resources\ReducedStockResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditReducedStock extends EditRecord
{
    protected static string $resource = ReducedStockResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}

<?php

namespace App\Filament\Proveedor\Resources\EarphoneRestocks\Pages;

use App\Filament\Proveedor\Resources\EarphoneRestocks\EarphoneRestockResource;
use Filament\Resources\Pages\ListRecords;

class ListEarphoneRestocks extends ListRecords
{
    protected static string $resource = EarphoneRestockResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}

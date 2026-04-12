<?php

namespace App\Filament\Resources\Earphones\Pages;

use App\Filament\Resources\Earphones\EarphoneResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListEarphones extends ListRecords
{
    protected static string $resource = EarphoneResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}

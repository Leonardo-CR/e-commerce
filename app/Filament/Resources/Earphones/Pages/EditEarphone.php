<?php

namespace App\Filament\Resources\Earphones\Pages;

use App\Filament\Resources\Earphones\EarphoneResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditEarphone extends EditRecord
{
    protected static string $resource = EarphoneResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}

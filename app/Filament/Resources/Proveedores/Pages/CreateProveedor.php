<?php

namespace App\Filament\Resources\Proveedores\Pages;

use App\Filament\Resources\Proveedores\ProveedorResource;
use App\Models\User;
use Filament\Resources\Pages\CreateRecord;

class CreateProveedor extends CreateRecord
{
    protected static string $resource = ProveedorResource::class;

    protected function afterCreate(): void
    {
        /** @var User $user */
        $user = $this->record;
        $user->syncRoles(['proveedor']);
    }
}

<?php

namespace App\Filament\Resources\Proveedores\Schemas;

use App\Models\Supplier;
use App\Models\User;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Hash;

class ProveedorForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Nombre del contacto')
                    ->required()
                    ->maxLength(120),

                TextInput::make('email')
                    ->label('Correo electrónico')
                    ->email()
                    ->required()
                    ->maxLength(150)
                    ->unique(table: User::class, column: 'email', ignoreRecord: true),

                TextInput::make('phone')
                    ->label('Teléfono')
                    ->tel()
                    ->maxLength(20),

                Select::make('supplier_id')
                    ->label('Empresa proveedora')
                    ->options(function ($record) {
                        return Supplier::query()
                            ->whereDoesntHave('user', function ($q) use ($record) {
                                if ($record) {
                                    $q->where('users.id', '!=', $record->id);
                                }
                            })
                            ->orderBy('name')
                            ->pluck('name', 'idSupplier');
                    })
                    ->required()
                    ->searchable()
                    ->helperText('Cada proveedor (empresa) sólo puede vincularse a un usuario.'),

                TextInput::make('password')
                    ->label('Contraseña')
                    ->password()
                    ->revealable()
                    ->minLength(8)
                    ->required(fn (string $operation) => $operation === 'create')
                    ->dehydrated(fn ($state) => filled($state))
                    ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                    ->helperText('Déjalo vacío al editar para no cambiarla.'),
            ]);
    }
}

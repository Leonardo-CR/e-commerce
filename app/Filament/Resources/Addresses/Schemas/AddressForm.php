<?php

namespace App\Filament\Resources\Addresses\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class AddressForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('street')
                    ->label('Calle')
                    ->required(),
                TextInput::make('number')
                    ->label('Número')
                    ->required(),
                TextInput::make('colony')
                    ->label('Colonia')
                    ->required(),
                TextInput::make('city')
                    ->label('Ciudad')
                    ->required(),
                TextInput::make('state')
                    ->label('Estado')
                    ->required(),
                TextInput::make('zip')
                    ->label('Código postal')
                    ->required(),
                Toggle::make('eliminated')
                    ->label('Eliminado'),
                Select::make('user_id')
                    ->label('Usuario')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->required(),
            ]);
    }
}

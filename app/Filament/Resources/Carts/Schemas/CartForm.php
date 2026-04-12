<?php

namespace App\Filament\Resources\Carts\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class CartForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('user_id')
                    ->label('Usuario')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->required(),
                TextInput::make('status')
                    ->label('Estado')
                    ->required(),
                DateTimePicker::make('expressAt')
                    ->label('Fecha de entrega exprés'),
            ]);
    }
}

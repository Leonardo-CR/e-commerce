<?php

namespace App\Filament\Resources\CartItems\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class CartItemForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('idCart')
                    ->label('Carrito')
                    ->relationship('cart', 'idCart')
                    ->searchable()
                    ->required(),
                Select::make('idEarphone')
                    ->label('Audífono')
                    ->relationship('earphone', 'name')
                    ->searchable()
                    ->required(),
                TextInput::make('quantity')
                    ->label('Cantidad')
                    ->required()
                    ->numeric(),
                TextInput::make('unit_price')
                    ->label('Precio unitario')
                    ->required()
                    ->numeric()
                    ->prefix('$'),
                TextInput::make('subtotal')
                    ->label('Subtotal')
                    ->required()
                    ->numeric()
                    ->prefix('$'),
            ]);
    }
}

<?php

namespace App\Filament\Resources\OrderItems\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class OrderItemForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('idOrder')
                    ->label('Pedido')
                    ->relationship('order', 'idOrder')
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
                TextInput::make('discount')
                    ->label('Descuento')
                    ->required()
                    ->numeric()
                    ->prefix('$')
                    ->default(0),
                TextInput::make('tax')
                    ->label('Impuesto')
                    ->required()
                    ->numeric()
                    ->prefix('$')
                    ->default(0),
            ]);
    }
}

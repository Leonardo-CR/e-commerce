<?php

namespace App\Filament\Resources\Orders\Schemas;

use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class OrderForm
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
                Select::make('idPayment')
                    ->label('Pago')
                    ->relationship('payment', 'idPayment')
                    ->searchable(),
                TextInput::make('status')
                    ->label('Estado')
                    ->required(),
                TextInput::make('totalAmount')
                    ->label('Monto total')
                    ->required()
                    ->numeric()
                    ->prefix('$'),
                TextInput::make('shippingCost')
                    ->label('Costo de envío')
                    ->required()
                    ->numeric()
                    ->prefix('$'),
                TextInput::make('shippingCompany')
                    ->label('Empresa de envío'),
                TextInput::make('TrackingNumber')
                    ->label('Número de rastreo'),
                Repeater::make('orderItems')
                    ->label('Productos de este pedido')
                    ->relationship('orderItems')
                    ->schema([
                        Select::make('idEarphone')
                            ->label('Producto')
                            ->relationship('earphone', 'name')
                            ->disabled(),
                        TextInput::make('quantity')
                            ->label('Cantidad')
                            ->numeric()
                            ->disabled(),
                        TextInput::make('unit_price')
                            ->label('Precio unitario')
                            ->numeric()
                            ->prefix('$')
                            ->disabled(),
                        TextInput::make('subtotal')
                            ->label('Subtotal')
                            ->numeric()
                            ->prefix('$')
                            ->disabled(),
                    ])
                    ->columnSpanFull()
                    ->disabled()
                    ->dehydrated(false),
            ]);
    }
}

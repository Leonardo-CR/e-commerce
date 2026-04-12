<?php

namespace App\Filament\Resources\Orders\Schemas;

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
            ]);
    }
}

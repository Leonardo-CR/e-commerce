<?php

namespace App\Filament\Resources\PurchaseItems\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class PurchaseItemForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('idPurchase')
                    ->label('Compra')
                    ->relationship('purchase', 'invoiceNumber')
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
                TextInput::make('unit_cost')
                    ->label('Costo unitario')
                    ->required()
                    ->numeric()
                    ->prefix('$'),
                DatePicker::make('received_date')
                    ->label('Fecha de recepción'),
            ]);
    }
}

<?php

namespace App\Filament\Resources\Purchases\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class PurchaseForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                DatePicker::make('purchaseDate')
                    ->label('Fecha de compra')
                    ->required(),
                TextInput::make('invoiceNumber')
                    ->label('Número de factura'),
                TextInput::make('paymentMethod')
                    ->label('Método de pago'),
                TextInput::make('totalAmount')
                    ->label('Monto total')
                    ->required()
                    ->numeric()
                    ->prefix('$'),
                TextInput::make('iva')
                    ->label('IVA')
                    ->required()
                    ->numeric()
                    ->prefix('$'),
                TextInput::make('shipping_cost')
                    ->label('Costo de envío')
                    ->required()
                    ->numeric()
                    ->prefix('$'),
                Textarea::make('notes')
                    ->label('Notas')
                    ->columnSpanFull(),
            ]);
    }
}

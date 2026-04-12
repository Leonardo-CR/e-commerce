<?php

namespace App\Filament\Resources\Refunds\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class RefundForm
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
                TextInput::make('status')
                    ->label('Estado')
                    ->required(),
                Textarea::make('reason')
                    ->label('Motivo')
                    ->required()
                    ->columnSpanFull(),
            ]);
    }
}

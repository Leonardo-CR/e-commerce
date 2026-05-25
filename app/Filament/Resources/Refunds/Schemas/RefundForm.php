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
                    ->required()
                    ->disabled(fn ($operation) => $operation === 'edit'),
                TextInput::make('user_email')
                    ->label('Email del usuario')
                    ->afterStateHydrated(function ($component, $record) {
                        $component->state($record?->order?->user?->email);
                    })
                    ->disabled()
                    ->dehydrated(false)
                    ->visible(fn ($operation) => $operation === 'edit'),
                Select::make('status')
                    ->label('Estado')
                    ->options([
                        'pending' => 'Pendiente',
                        'resolved' => 'Resuelto',
                    ])
                    ->required(),
                Textarea::make('reason')
                    ->label('Motivo')
                    ->required()
                    ->disabled(fn ($operation) => $operation === 'edit')
                    ->columnSpanFull(),
            ]);
    }
}

<?php

namespace App\Filament\Resources\Earphones\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PurchaseItemsRelationManager extends RelationManager
{
    protected static string $relationship = 'purchaseItems';

    protected static ?string $title = 'Historial de costos';

    protected static ?string $modelLabel = 'compra';

    protected static ?string $pluralModelLabel = 'compras';

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('idPurchase_Item')
            ->defaultSort('received_date', 'desc')
            ->columns([
                TextColumn::make('received_date')
                    ->label('Fecha')
                    ->date('d/m/Y')
                    ->sortable(),

                TextColumn::make('purchase.supplier.name')
                    ->label('Proveedor')
                    ->placeholder('Sin proveedor')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('quantity')
                    ->label('Cantidad')
                    ->numeric()
                    ->sortable(),

                TextColumn::make('unit_cost')
                    ->label('Costo unitario')
                    ->money('MXN')
                    ->sortable(),

                TextColumn::make('subtotal')
                    ->label('Subtotal')
                    ->state(fn ($record) => (float) $record->quantity * (float) $record->unit_cost)
                    ->money('MXN'),

                TextColumn::make('purchase.invoiceNumber')
                    ->label('Factura')
                    ->placeholder('—')
                    ->toggleable(),
            ])
            ->headerActions([])
            ->recordActions([])
            ->toolbarActions([]);
    }
}

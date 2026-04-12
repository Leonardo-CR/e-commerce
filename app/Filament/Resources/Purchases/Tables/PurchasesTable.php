<?php

namespace App\Filament\Resources\Purchases\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PurchasesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('purchaseDate')
                    ->label('Fecha de compra')
                    ->date('d/m/Y')
                    ->sortable(),
                TextColumn::make('invoiceNumber')
                    ->label('Número de factura')
                    ->searchable(),
                TextColumn::make('paymentMethod')
                    ->label('Método de pago')
                    ->searchable(),
                TextColumn::make('totalAmount')
                    ->label('Monto total')
                    ->money('MXN')
                    ->sortable(),
                TextColumn::make('iva')
                    ->label('IVA')
                    ->money('MXN')
                    ->sortable(),
                TextColumn::make('shipping_cost')
                    ->label('Costo de envío')
                    ->money('MXN')
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Creado')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->label('Actualizado')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make()->label('Editar'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()->label('Eliminar seleccionados'),
                ]),
            ]);
    }
}

<?php

namespace App\Filament\Resources\Earphones\Tables;

use App\Models\Earphone;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class EarphonesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn ($query) => $query->with('purchaseItems'))
            ->columns([
                ImageColumn::make('image')
                    ->label('Imagen')
                    ->disk('public_uploads'),
                TextColumn::make('name')
                    ->label('Nombre')
                    ->searchable(),
                TextColumn::make('price')
                    ->label('Precio venta')
                    ->money('MXN')
                    ->sortable(),
                TextColumn::make('stock')
                    ->label('Stock')
                    ->numeric()
                    ->sortable(),

                TextColumn::make('last_unit_cost')
                    ->label('Último costo')
                    ->state(fn (Earphone $record) => self::lastUnitCost($record))
                    ->money('MXN')
                    ->placeholder('—'),

                TextColumn::make('avg_unit_cost')
                    ->label('Costo prom.')
                    ->state(fn (Earphone $record) => self::avgUnitCost($record))
                    ->money('MXN')
                    ->placeholder('—'),

                TextColumn::make('margin_percent')
                    ->label('Margen %')
                    ->state(function (Earphone $record): ?string {
                        $cost = self::lastUnitCost($record);

                        if (!$cost || (float) $record->price <= 0) {
                            return null;
                        }

                        $margin = (((float) $record->price) - $cost) / $cost * 100;

                        return number_format($margin, 1) . ' %';
                    })
                    ->badge()
                    ->color(function (Earphone $record): ?string {
                        $cost = self::lastUnitCost($record);

                        if (!$cost) {
                            return null;
                        }

                        $margin = (((float) $record->price) - $cost) / $cost * 100;

                        return match (true) {
                            $margin >= 40 => 'success',
                            $margin >= 15 => 'warning',
                            default       => 'danger',
                        };
                    })
                    ->placeholder('—'),

                TextColumn::make('supplier.name')
                    ->label('Proveedor')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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

    private static function lastUnitCost(Earphone $record): ?float
    {
        $latest = $record->purchaseItems
            ->sortByDesc(fn ($p) => $p->received_date ?? $p->created_at)
            ->first();

        return $latest ? (float) $latest->unit_cost : null;
    }

    private static function avgUnitCost(Earphone $record): ?float
    {
        $items = $record->purchaseItems;

        if ($items->isEmpty()) {
            return null;
        }

        $totalQty  = $items->sum(fn ($p) => (int) $p->quantity);
        $totalCost = $items->sum(fn ($p) => (int) $p->quantity * (float) $p->unit_cost);

        return $totalQty > 0 ? $totalCost / $totalQty : null;
    }
}

<?php

namespace App\Filament\Resources\Proveedores\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ProveedoresTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Contacto')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('email')
                    ->label('Correo')
                    ->searchable(),
                TextColumn::make('supplier.name')
                    ->label('Empresa')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('phone')
                    ->label('Teléfono'),
                TextColumn::make('last_login')
                    ->label('Último acceso')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->placeholder('Nunca'),
                TextColumn::make('created_at')
                    ->label('Alta')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(),
            ])
            ->recordActions([
                EditAction::make()->label('Editar'),
                DeleteAction::make()->label('Eliminar'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()->label('Eliminar seleccionados'),
                ]),
            ]);
    }
}

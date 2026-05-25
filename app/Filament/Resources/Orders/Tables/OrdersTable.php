<?php

namespace App\Filament\Resources\Orders\Tables;

use App\Models\Order;
use App\Services\EnviaService;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Select;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class OrdersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('idOrder')
                    ->label('#')
                    ->sortable(),
                TextColumn::make('user.name')
                    ->label('Usuario')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('status')
                    ->label('Estado')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'paid'       => 'success',
                        'pending'    => 'warning',
                        'shipped'    => 'info',
                        'failed'     => 'danger',
                        default      => 'gray',
                    })
                    ->searchable(),
                TextColumn::make('totalAmount')
                    ->label('Total')
                    ->money('MXN')
                    ->sortable(),
                TextColumn::make('shippingCost')
                    ->label('Costo envío')
                    ->money('MXN')
                    ->sortable(),
                TextColumn::make('shippingCompany')
                    ->label('Paquetería')
                    ->searchable(),
                TextColumn::make('TrackingNumber')
                    ->label('Tracking')
                    ->searchable()
                    ->copyable()
                    ->copyMessage('Número copiado'),
                TextColumn::make('created_at')
                    ->label('Creado')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([])
            ->recordActions([
                Action::make('generateShipping')
                    ->label('Generar guía')
                    ->icon('heroicon-o-truck')
                    ->color('success')
                    ->visible(fn (Order $record): bool =>
                        $record->status === 'paid' && empty($record->TrackingNumber)
                    )
                    ->form(fn (Order $record): array => [
                        Select::make('rate')
                            ->label('Servicio de envío disponible')
                            ->options(function () use ($record): array {
                                try {
                                    $rates = app(EnviaService::class)->getRates($record);
                                    return collect($rates)
                                        ->mapWithKeys(fn ($r) => [
                                            $r['carrier'] . '|' . $r['service'] =>
                                                strtoupper($r['carrier'])
                                                . ' — ' . ($r['serviceDescription'] ?? $r['service'])
                                                . '   $' . number_format((float) $r['totalPrice'], 2)
                                                . ' MXN'
                                                . (isset($r['deliveryEstimate']) ? '  (' . $r['deliveryEstimate'] . ')' : ''),
                                        ])
                                        ->all();
                                } catch (\Throwable $e) {
                                    return ['error' => 'Error al obtener tarifas: ' . $e->getMessage()];
                                }
                            })
                            ->required()
                            ->helperText('Se cotiza en tiempo real con la dirección de entrega del usuario.'),
                    ])
                    ->action(function (Order $record, array $data): void {
                        [$carrier, $service] = explode('|', $data['rate']);

                        try {
                            $result = app(EnviaService::class)->generateLabel($record, $carrier, $service);

                            $record->update([
                                'TrackingNumber'  => $result['trackingNumber'] ?? null,
                                'shippingCompany' => strtoupper($result['carrier'] ?? $carrier),
                                'shippingCost'    => (float) ($result['totalPrice'] ?? 0),
                                'status'          => 'shipped',
                            ]);

                            Notification::make()
                                ->title('¡Guía generada!')
                                ->body(
                                    'Tracking: **' . ($result['trackingNumber'] ?? '–') . '**'
                                    . "\n[Descargar PDF](" . ($result['label'] ?? '#') . ')'
                                )
                                ->success()
                                ->persistent()
                                ->send();
                        } catch (\Throwable $e) {
                            Notification::make()
                                ->title('Error al generar guía')
                                ->body($e->getMessage())
                                ->danger()
                                ->send();
                        }
                    })
                    ->modalHeading('Generar guía de envío')
                    ->modalSubmitActionLabel('Generar y guardar')
                    ->requiresConfirmation(false),

                Action::make('trackShipment')
                    ->label('Rastrear')
                    ->icon('heroicon-o-magnifying-glass')
                    ->color('info')
                    ->visible(fn (Order $record): bool => !empty($record->TrackingNumber))
                    ->action(function (Order $record): void {
                        try {
                            $tracking = app(EnviaService::class)->track($record->TrackingNumber);

                            $status = $tracking['status'] ?? 'Sin información';
                            $events = collect($tracking['events'] ?? [])->take(3)->map(
                                fn ($e) => ($e['timestamp'] ?? '') . ' — ' . ($e['description'] ?? '')
                            )->implode("\n");

                            Notification::make()
                                ->title('Estado: ' . $status)
                                ->body($events ?: 'No hay eventos registrados.')
                                ->info()
                                ->persistent()
                                ->send();
                        } catch (\Throwable $e) {
                            Notification::make()
                                ->title('Error al rastrear')
                                ->body($e->getMessage())
                                ->danger()
                                ->send();
                        }
                    }),

                ViewAction::make()->label('Ver'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()->label('Eliminar seleccionados'),
                ]),
            ]);
    }
}

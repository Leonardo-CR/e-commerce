<?php

namespace App\Filament\Proveedor\Resources\EarphoneRestocks\Tables;

use App\Models\Color;
use App\Models\Earphone;
use App\Models\Purchase;
use App\Models\PurchaseItem;
use Filament\Actions\Action;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EarphoneRestocksTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Producto')
                    ->searchable(),
                TextColumn::make('price')
                    ->label('Precio venta')
                    ->money('MXN')
                    ->sortable(),
                TextColumn::make('stock')
                    ->label('Stock total')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('mis_variantes')
                    ->label('Mis variantes')
                    ->state(function (Earphone $record): string {
                        $supplierId = Auth::user()->supplier_id;

                        return collect($record->colors ?? [])
                            ->filter(fn ($c) => ($c['idSupplier'] ?? null) == $supplierId)
                            ->map(function ($c) {
                                $color = Color::find($c['color_id'] ?? null);

                                return ($color?->name ?? 'Color') . ' (' . ($c['stock'] ?? 0) . ')';
                            })
                            ->implode(', ');
                    }),
            ])
            ->recordActions([
                self::restockAction(),
            ]);
    }

    protected static function restockAction(): Action
    {
        return Action::make('reabastecer')
            ->label('Reabastecer')
            ->icon(Heroicon::OutlinedArrowPath)
            ->color('success')
            ->modalHeading('Reabastecer producto')
            ->modalSubmitActionLabel('Reabastecer')
            ->fillForm(function (Earphone $record): array {
                $supplierId = Auth::user()->supplier_id;

                $items = collect($record->colors ?? [])
                    ->filter(fn ($c) => ($c['idSupplier'] ?? null) == $supplierId)
                    ->map(function ($c) {
                        $color = Color::find($c['color_id'] ?? null);

                        return [
                            'color_id'     => $c['color_id'] ?? null,
                            'color_name'   => $color?->name ?? 'Color',
                            'stock_actual' => $c['stock'] ?? 0,
                            'cantidad'     => 0,
                            'unit_cost'    => null,
                        ];
                    })
                    ->values()
                    ->toArray();

                return ['items' => $items];
            })
            ->schema([
                TextInput::make('invoice_number')
                    ->label('Número de factura')
                    ->maxLength(50),

                Repeater::make('items')
                    ->label('Variantes')
                    ->schema([
                        Hidden::make('color_id'),
                        TextInput::make('color_name')
                            ->label('Color')
                            ->disabled()
                            ->dehydrated(false),
                        TextInput::make('stock_actual')
                            ->label('Stock actual')
                            ->disabled()
                            ->dehydrated(false),
                        TextInput::make('cantidad')
                            ->label('Agregar')
                            ->integer()
                            ->minValue(0)
                            ->default(0)
                            ->required(),
                        TextInput::make('unit_cost')
                            ->label('Costo unitario')
                            ->numeric()
                            ->minValue(0)
                            ->prefix('$')
                            ->required(),
                    ])
                    ->columns(4)
                    ->addable(false)
                    ->deletable(false)
                    ->reorderable(false),
            ])
            ->action(function (array $data, Earphone $record) {
                $supplierId = Auth::user()->supplier_id;

                $items = collect($data['items'] ?? [])
                    ->filter(fn ($r) => ((int) ($r['cantidad'] ?? 0)) > 0);

                if ($items->isEmpty()) {
                    Notification::make()
                        ->title('No se ingresó ninguna cantidad.')
                        ->warning()
                        ->send();
                    return;
                }

                DB::transaction(function () use ($items, $record, $supplierId, $data) {
                    $subtotal = $items->sum(fn ($r) => (int) $r['cantidad'] * (float) $r['unit_cost']);
                    $iva      = round($subtotal * 0.16, 2);

                    $purchase = Purchase::create([
                        'idSupplier'    => $supplierId,
                        'purchaseDate'  => now()->toDateString(),
                        'iva'           => $iva,
                        'shipping_cost' => 0,
                        'totalAmount'   => round($subtotal + $iva, 2),
                        'invoiceNumber' => $data['invoice_number'] ?? null,
                        'paymentMethod' => 'pendiente',
                        'notes'         => 'Reabastecimiento desde panel proveedor',
                    ]);

                    $colors = $record->colors ?? [];

                    foreach ($items as $r) {
                        foreach ($colors as $i => $c) {
                            if (
                                ($c['color_id'] ?? null) == $r['color_id']
                                && ($c['idSupplier'] ?? null) == $supplierId
                            ) {
                                $colors[$i]['stock'] = ($c['stock'] ?? 0) + (int) $r['cantidad'];
                                break;
                            }
                        }

                        PurchaseItem::create([
                            'idPurchase'    => $purchase->idPurchase,
                            'idEarphone'    => $record->idEarphone,
                            'quantity'      => (int) $r['cantidad'],
                            'unit_cost'     => (float) $r['unit_cost'],
                            'received_date' => now()->toDateString(),
                        ]);
                    }

                    $record->colors = $colors;
                    $record->stock  = collect($colors)->sum('stock');
                    $record->save();
                });

                Notification::make()
                    ->title('Stock reabastecido con éxito.')
                    ->success()
                    ->send();
            });
    }
}

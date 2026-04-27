<?php

namespace App\Filament\Resources\Earphones\Schemas;

use App\Models\Color;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class EarphoneForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Nombre')
                    ->required(),
                TextInput::make('price')
                    ->label('Precio')
                    ->required()
                    ->numeric()
                    ->prefix('$'),
                TextInput::make('stock')
                    ->label('Stock Total')
                    ->helperText('Se calcula automáticamente sumando el stock de cada color.')
                    ->readOnly()
                    ->numeric()
                    ->placeholder(function (callable $get) {
                        $colors = $get('colors') ?? [];
                        return collect($colors)->sum('stock');
                    })
                    ->dehydrated(true)
                    ->afterStateHydrated(function (TextInput $component, $state, $get) {
                        $colors = $get('colors') ?? [];
                        $component->state(collect($colors)->sum('stock'));
                    }),
                Textarea::make('description')
                    ->label('Descripción')
                    ->columnSpanFull(),
                \Filament\Forms\Components\Repeater::make('colors')
                    ->label('Colores y Stock')
                    ->live()
                    ->schema([
                        Select::make('color_id')
                            ->label('Color')
                            ->options(Color::pluck('name', 'id'))
                            ->searchable()
                            ->required(),
                        Select::make('idSupplier')
                            ->label('Proveedor de este color')
                            ->options(\App\Models\Supplier::pluck('name', 'idSupplier'))
                            ->searchable()
                            ->required(),
                        TextInput::make('stock')
                            ->label('Stock de este color')
                            ->numeric()
                            ->required()
                            ->default(0)
                            ->live(onBlur: true),
                        FileUpload::make('image')
                            ->label('Imagen de este color')
                            ->image()
                            ->disk('public_uploads')
                            ->directory('images/products')
                            ->visibility('public')
                            ->required(),
                    ])
                    ->afterStateUpdated(function ($state, callable $set) {
                        $total = collect($state)->sum('stock');
                        $set('stock', $total);
                    })
                    ->grid(2)
                    ->columnSpanFull()
                    ->itemLabel(function (array $state): ?string {
                        if (empty($state['color_id'])) {
                            return null;
                        }
                        return Color::find($state['color_id'])?->name;
                    }),
            ]);
    }
}

<?php

namespace App\Filament\Proveedor\Resources\EarphoneRestocks;

use App\Filament\Proveedor\Resources\EarphoneRestocks\Pages\ListEarphoneRestocks;
use App\Filament\Proveedor\Resources\EarphoneRestocks\Tables\EarphoneRestocksTable;
use App\Models\Earphone;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class EarphoneRestockResource extends Resource
{
    protected static ?string $model = Earphone::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedArrowPath;

    protected static ?string $modelLabel = 'Producto';

    protected static ?string $pluralModelLabel = 'Mis Productos';

    protected static ?string $navigationLabel = 'Reabastecer';

    public static function getEloquentQuery(): Builder
    {
        $supplierId = (int) Auth::user()?->supplier_id;
        $query      = parent::getEloquentQuery();
        $driver     = $query->getConnection()->getDriverName();

        if (in_array($driver, ['mysql', 'mariadb'], true)) {
            return $query->whereRaw(
                'JSON_CONTAINS(JSON_EXTRACT(colors, "$[*].idSupplier"), ?)',
                [(string) $supplierId]
            );
        }

        return $query->where('colors', 'like', '%"idSupplier":' . $supplierId . '%');
    }

    public static function table(Table $table): Table
    {
        return EarphoneRestocksTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListEarphoneRestocks::route('/'),
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }
}

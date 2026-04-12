<?php

namespace App\Filament\Resources\Earphones;

use App\Filament\Resources\Earphones\Pages\CreateEarphone;
use App\Filament\Resources\Earphones\Pages\EditEarphone;
use App\Filament\Resources\Earphones\Pages\ListEarphones;
use App\Filament\Resources\Earphones\Schemas\EarphoneForm;
use App\Filament\Resources\Earphones\Tables\EarphonesTable;
use App\Models\Earphone;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class EarphoneResource extends Resource
{
    protected static ?string $model = Earphone::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedMusicalNote;

    protected static ?string $modelLabel = 'Audífono';

    protected static ?string $pluralModelLabel = 'Audífonos';

    protected static ?string $navigationLabel = 'Audífonos';

    protected static \UnitEnum|string|null $navigationGroup = 'Inventario';

    public static function form(Schema $schema): Schema
    {
        return EarphoneForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return EarphonesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListEarphones::route('/'),
            'create' => CreateEarphone::route('/create'),
            'edit'   => EditEarphone::route('/{record}/edit'),
        ];
    }
}

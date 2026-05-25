<?php

namespace App\Filament\Pages;

use App\Models\Setting;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use UnitEnum;

class ShippingOriginSettings extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedTruck;

    protected static UnitEnum|string|null $navigationGroup = 'Administración';

    protected static ?string $navigationLabel = 'Configuración de envíos';

    protected static ?string $title = 'Dirección de origen de envíos';

    protected string $view = 'filament.pages.shipping-origin-settings';

    public ?array $data = [];

    public function mount(): void
    {
        $cfg = Setting::get('shipping.origin', config('services.envia.origin'));

        $this->form->fill([
            'name'        => $cfg['name']        ?? null,
            'phone'       => $cfg['phone']       ?? null,
            'street'      => $cfg['street']      ?? null,
            'city'        => $cfg['city']        ?? null,
            'state'       => $cfg['state']       ?? null,
            'postal_code' => $cfg['postal_code'] ?? null,
        ]);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Nombre del remitente')
                    ->required()
                    ->maxLength(120),
                TextInput::make('phone')
                    ->label('Teléfono')
                    ->tel()
                    ->required()
                    ->maxLength(20)
                    ->helperText('Formato internacional, p. ej. +528180000000'),
                TextInput::make('street')
                    ->label('Calle y número')
                    ->required()
                    ->maxLength(200),
                TextInput::make('city')
                    ->label('Ciudad')
                    ->required()
                    ->maxLength(100),
                TextInput::make('state')
                    ->label('Estado')
                    ->required()
                    ->maxLength(60)
                    ->helperText('Nombre del estado o código (p. ej. NL, Jalisco).'),
                TextInput::make('postal_code')
                    ->label('Código postal')
                    ->required()
                    ->maxLength(10),
            ])
            ->statePath('data');
    }

    protected function getFormActions(): array
    {
        return [
            Action::make('save')
                ->label('Guardar')
                ->submit('save'),
        ];
    }

    public function save(): void
    {
        Setting::set('shipping.origin', $this->form->getState());

        Notification::make()
            ->title('Dirección de origen actualizada.')
            ->success()
            ->send();
    }

    public static function canAccess(): bool
    {
        $user = auth()->user();

        return $user && $user->hasAnyRole(['super_admin', 'admin']);
    }
}

<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Nombre')
                    ->required(),
                TextInput::make('CURP')
                    ->label('CURP'),
                TextInput::make('phone')
                    ->label('Teléfono')
                    ->tel(),
                TextInput::make('email')
                    ->label('Correo electrónico')
                    ->email()
                    ->required(),
                TextInput::make('password')
                    ->label('Contraseña')
                    ->password()
                    ->required(),
                Toggle::make('is_admin')
                    ->label('Es administrador')
                    ->required(),
                Toggle::make('is_superuser')
                    ->label('Es superusuario')
                    ->required(),
                DateTimePicker::make('last_login')
                    ->label('Último inicio de sesión'),
                DateTimePicker::make('email_verified_at')
                    ->label('Correo verificado en'),
            ]);
    }
}

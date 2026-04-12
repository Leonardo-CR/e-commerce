<?php

namespace App\Filament\Resources\Reviews\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class ReviewForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('user_id')
                    ->label('Usuario')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->required(),
                Select::make('idEarphone')
                    ->label('Audífono')
                    ->relationship('earphone', 'name')
                    ->searchable()
                    ->required(),
                Textarea::make('comment')
                    ->label('Comentario')
                    ->required()
                    ->columnSpanFull(),
            ]);
    }
}

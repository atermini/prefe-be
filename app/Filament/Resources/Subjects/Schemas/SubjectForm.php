<?php

namespace App\Filament\Resources\Subjects\Schemas;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class SubjectForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Nome')
                    ->placeholder('Es. Mario Rossi, Papa Francesco, mio cugino Luca')
                    ->required()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true),
                Textarea::make('description')
                    ->label('Descrizione / contesto')
                    ->placeholder("Note brevi che aiutano l'AI: 'amico d'infanzia fissato col padel', 'collega sempre in ritardo'...")
                    ->helperText("Opzionale ma consigliato: più contesto dai, più le domande saranno calzanti.")
                    ->rows(3)
                    ->maxLength(1000)
                    ->columnSpanFull(),
            ]);
    }
}

<?php

namespace App\Filament\Resources\Questions\Schemas;

use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class QuestionInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Dettagli domanda')
                    ->schema([
                        TextEntry::make('prompt')
                            ->columnSpanFull(),
                        TextEntry::make('option_a')
                            ->label('Risposta A'),
                        TextEntry::make('option_b')
                            ->label('Risposta B'),
                        TextEntry::make('active_on')
                            ->date()
                            ->label('Pubblicata il'),
                        TextEntry::make('answers_count')
                            ->label('Totale risposte')
                            ->state(fn ($record) => $record->answers()->count()),
                    ])
                    ->columns(2),
            ]);
    }
}

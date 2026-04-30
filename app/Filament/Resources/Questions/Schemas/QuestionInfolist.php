<?php

namespace App\Filament\Resources\Questions\Schemas;

use App\Models\Question;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class QuestionInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Dettagli domanda')
                    ->schema([
                        TextEntry::make('intro')
                            ->label('Incipit fisso')
                            ->state(Question::introText()),
                        TextEntry::make('full_question')
                            ->label('Anteprima completa')
                            ->state(fn ($record) => $record->fullText())
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

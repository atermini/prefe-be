<?php

namespace App\Filament\Resources\Answers\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class AnswerInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('question.id')
                    ->label('ID domanda'),
                TextEntry::make('question_full_text')
                    ->label('Domanda')
                    ->state(fn ($record) => $record->question?->fullText()),
                TextEntry::make('user.name')
                    ->label('Utente'),
                TextEntry::make('selected_option')
                    ->badge(),
                IconEntry::make('is_shared')
                    ->label('Condivisa')
                    ->boolean(),
                TextEntry::make('answered_at')
                    ->dateTime(),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}

<?php

namespace App\Filament\Resources\Questions\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class QuestionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Domanda del giorno')
                    ->schema([
                        Textarea::make('prompt')
                            ->label('Testo della domanda')
                            ->required()
                            ->rows(4)
                            ->columnSpanFull(),
                        TextInput::make('option_a')
                            ->label('Risposta A')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('option_b')
                            ->label('Risposta B')
                            ->required()
                            ->maxLength(255),
                        DatePicker::make('active_on')
                            ->label('Data di pubblicazione')
                            ->required()
                            ->unique(ignoreRecord: true),
                    ])
                    ->columns(2),
            ]);
    }
}

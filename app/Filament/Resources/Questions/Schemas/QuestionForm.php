<?php

namespace App\Filament\Resources\Questions\Schemas;

use App\Models\Question;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Section;
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
                        Placeholder::make('intro')
                            ->label('Incipit fisso')
                            ->content(Question::introText())
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

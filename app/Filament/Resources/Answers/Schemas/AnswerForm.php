<?php

namespace App\Filament\Resources\Answers\Schemas;

use App\Models\Question;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class AnswerForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Risposta')
                    ->schema([
                        Select::make('question_id')
                            ->options(fn () => Question::query()
                                ->orderByDesc('active_on')
                                ->get()
                                ->mapWithKeys(fn (Question $question) => [
                                    $question->id => $question->fullText(),
                                ])
                                ->all())
                            ->searchable()
                            ->preload()
                            ->required(),
                        Select::make('user_id')
                            ->relationship('user', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),
                        Select::make('selected_option')
                            ->options([
                                'A' => 'A',
                                'B' => 'B',
                            ])
                            ->required(),
                        Toggle::make('is_shared')
                            ->label('Condivisa nel feed'),
                        DateTimePicker::make('answered_at')
                            ->required(),
                    ])
                    ->columns(2),
            ]);
    }
}

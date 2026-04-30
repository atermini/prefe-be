<?php

namespace App\Filament\Resources\Answers\Tables;

use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class AnswersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('question_full_text')
                    ->label('Domanda')
                    ->state(fn ($record) => $record->question?->fullText())
                    ->limit(50)
                    ->searchable(query: function ($query, string $search) {
                        return $query
                            ->whereHas('question', fn ($query) => $query
                                ->where('option_a', 'like', "%{$search}%")
                                ->orWhere('option_b', 'like', "%{$search}%"));
                    }),
                TextColumn::make('user.name')
                    ->searchable(),
                TextColumn::make('selected_option')
                    ->badge()
                    ->searchable(),
                IconColumn::make('is_shared')
                    ->boolean(),
                TextColumn::make('answered_at')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('selected_option')
                    ->options([
                        'A' => 'A',
                        'B' => 'B',
                    ]),
                TernaryFilter::make('is_shared')
                    ->label('Condivisa'),
            ])
            ->recordActions([
                ActionGroup::make([
                    ViewAction::make(),
                ]),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}

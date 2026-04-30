<?php

namespace App\Filament\Resources\Questions\Tables;

use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class QuestionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('full_question')
                    ->label('Domanda')
                    ->state(fn ($record) => $record->fullText())
                    ->limit(80),
                TextColumn::make('option_a')
                    ->searchable(),
                TextColumn::make('option_b')
                    ->searchable(),
                TextColumn::make('answers_count')
                    ->counts('answers')
                    ->label('Risposte')
                    ->sortable(),
                TextColumn::make('active_on')
                    ->date()
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
                TernaryFilter::make('today')
                    ->label('Domanda di oggi')
                    ->queries(
                        true: fn ($query) => $query->whereDate('active_on', today()),
                        false: fn ($query) => $query->whereDate('active_on', '!=', today()),
                        blank: fn ($query) => $query,
                    ),
                Filter::make('active_on')
                    ->schema([
                        DatePicker::make('active_from'),
                        DatePicker::make('active_until'),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when($data['active_from'] ?? null, fn ($query, $date) => $query->whereDate('active_on', '>=', $date))
                            ->when($data['active_until'] ?? null, fn ($query, $date) => $query->whereDate('active_on', '<=', $date));
                    }),
            ])
            ->recordActions([
                ActionGroup::make([
                    ViewAction::make(),
                    EditAction::make(),
                ]),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}

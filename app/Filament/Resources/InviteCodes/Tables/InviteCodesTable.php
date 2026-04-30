<?php

namespace App\Filament\Resources\InviteCodes\Tables;

use Filament\Actions\DeleteAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class InviteCodesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('code')
                    ->label('Codice')
                    ->copyable()
                    ->fontFamily('mono')
                    ->searchable(),
                TextColumn::make('uses_count')
                    ->label('Usi')
                    ->alignCenter(),
                TextColumn::make('max_uses')
                    ->label('Max usi')
                    ->alignCenter(),
                TextColumn::make('remaining')
                    ->label('Rimanenti')
                    ->state(fn (mixed $record): int => $record->remaining())
                    ->alignCenter()
                    ->badge()
                    ->color(fn (int $state): string => $state === 0 ? 'danger' : 'success'),
                TextColumn::make('created_at')
                    ->label('Creato il')
                    ->date('d/m/Y')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->recordActions([
                DeleteAction::make(),
            ]);
    }
}

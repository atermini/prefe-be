<?php

namespace App\Filament\Resources\Friendships\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Schemas\Schema;

class FriendshipForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Relazione')
                    ->schema([
                        Select::make('requester_id')
                            ->relationship('requester', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),
                        Select::make('addressee_id')
                            ->relationship('addressee', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),
                        Select::make('status')
                            ->options([
                                'pending' => 'Pending',
                                'accepted' => 'Accepted',
                                'declined' => 'Declined',
                            ])
                            ->required()
                            ->default('pending'),
                        DateTimePicker::make('responded_at'),
                    ])
                    ->columns(2),
            ]);
    }
}

<?php

namespace App\Filament\Resources\DeviceTokens\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class DeviceTokenForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Device token')
                    ->schema([
                        Select::make('user_id')
                            ->relationship('user', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),
                        TextInput::make('token')
                            ->required()
                            ->maxLength(255),
                        Select::make('platform')
                            ->options([
                                'android' => 'Android',
                                'ios' => 'iOS',
                            ])
                            ->required(),
                        DateTimePicker::make('last_used_at'),
                    ])
                    ->columns(2),
            ]);
    }
}

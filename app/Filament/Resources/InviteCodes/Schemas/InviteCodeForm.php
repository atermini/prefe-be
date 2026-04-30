<?php

namespace App\Filament\Resources\InviteCodes\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class InviteCodeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('max_uses')
                    ->label('Numero massimo di usi')
                    ->integer()
                    ->minValue(1)
                    ->required(),
            ]);
    }
}

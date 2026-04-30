<?php

namespace App\Filament\Resources\Friendships\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class FriendshipInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('requester.name')
                    ->label('Requester'),
                TextEntry::make('addressee.name')
                    ->label('Addressee'),
                TextEntry::make('status')
                    ->badge(),
                TextEntry::make('responded_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}

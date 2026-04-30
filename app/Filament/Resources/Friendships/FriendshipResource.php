<?php

namespace App\Filament\Resources\Friendships;

use App\Filament\Resources\Friendships\Pages\CreateFriendship;
use App\Filament\Resources\Friendships\Pages\EditFriendship;
use App\Filament\Resources\Friendships\Pages\ListFriendships;
use App\Filament\Resources\Friendships\Pages\ViewFriendship;
use App\Filament\Resources\Friendships\Schemas\FriendshipForm;
use App\Filament\Resources\Friendships\Schemas\FriendshipInfolist;
use App\Filament\Resources\Friendships\Tables\FriendshipsTable;
use App\Models\Friendship;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class FriendshipResource extends Resource
{
    protected static ?string $model = Friendship::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUsers;

    protected static ?string $navigationLabel = 'Amicizie';

    protected static ?string $modelLabel = 'Amicizia';

    protected static ?string $pluralModelLabel = 'Amicizie';

    protected static string|\UnitEnum|null $navigationGroup = 'Social';

    public static function form(Schema $schema): Schema
    {
        return FriendshipForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return FriendshipInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return FriendshipsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListFriendships::route('/'),
            'create' => CreateFriendship::route('/create'),
            'view' => ViewFriendship::route('/{record}'),
            'edit' => EditFriendship::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        $count = static::getModel()::query()
            ->where('status', Friendship::STATUS_PENDING)
            ->count();

        return $count > 0 ? (string) $count : null;
    }
}

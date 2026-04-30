<?php

namespace App\Filament\Resources\DeviceTokens;

use App\Filament\Resources\DeviceTokens\Pages\CreateDeviceToken;
use App\Filament\Resources\DeviceTokens\Pages\EditDeviceToken;
use App\Filament\Resources\DeviceTokens\Pages\ListDeviceTokens;
use App\Filament\Resources\DeviceTokens\Pages\ViewDeviceToken;
use App\Filament\Resources\DeviceTokens\Schemas\DeviceTokenForm;
use App\Filament\Resources\DeviceTokens\Schemas\DeviceTokenInfolist;
use App\Filament\Resources\DeviceTokens\Tables\DeviceTokensTable;
use App\Models\DeviceToken;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class DeviceTokenResource extends Resource
{
    protected static ?string $model = DeviceToken::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDevicePhoneMobile;

    protected static ?string $navigationLabel = 'Device Token';

    protected static ?string $modelLabel = 'Device token';

    protected static ?string $pluralModelLabel = 'Device token';

    protected static string|\UnitEnum|null $navigationGroup = 'Attività';

    public static function form(Schema $schema): Schema
    {
        return DeviceTokenForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return DeviceTokenInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return DeviceTokensTable::configure($table);
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
            'index' => ListDeviceTokens::route('/'),
            'create' => CreateDeviceToken::route('/create'),
            'view' => ViewDeviceToken::route('/{record}'),
            'edit' => EditDeviceToken::route('/{record}/edit'),
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canEdit(Model $record): bool
    {
        return false;
    }
}

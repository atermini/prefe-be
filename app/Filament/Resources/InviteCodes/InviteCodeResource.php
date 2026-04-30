<?php

namespace App\Filament\Resources\InviteCodes;

use App\Filament\Resources\InviteCodes\Pages\CreateInviteCode;
use App\Filament\Resources\InviteCodes\Pages\ListInviteCodes;
use App\Filament\Resources\InviteCodes\Schemas\InviteCodeForm;
use App\Filament\Resources\InviteCodes\Tables\InviteCodesTable;
use App\Models\InviteCode;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class InviteCodeResource extends Resource
{
    protected static ?string $model = InviteCode::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedTicket;

    protected static ?string $navigationLabel = 'Codici invito';

    protected static ?string $modelLabel = 'Codice invito';

    protected static ?string $pluralModelLabel = 'Codici invito';

    protected static string|\UnitEnum|null $navigationGroup = 'Contenuti';

    public static function form(Schema $schema): Schema
    {
        return InviteCodeForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return InviteCodesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListInviteCodes::route('/'),
            'create' => CreateInviteCode::route('/create'),
        ];
    }
}

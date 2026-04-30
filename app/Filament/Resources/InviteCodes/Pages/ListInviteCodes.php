<?php

namespace App\Filament\Resources\InviteCodes\Pages;

use App\Filament\Resources\InviteCodes\InviteCodeResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListInviteCodes extends ListRecords
{
    protected static string $resource = InviteCodeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()->label('Genera codice'),
        ];
    }
}

<?php

namespace App\Filament\Resources\InviteCodes\Pages;

use App\Filament\Resources\InviteCodes\InviteCodeResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Str;

class CreateInviteCode extends CreateRecord
{
    protected static string $resource = InviteCodeResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['code'] = strtoupper(Str::random(4).'-'.Str::random(4));

        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}

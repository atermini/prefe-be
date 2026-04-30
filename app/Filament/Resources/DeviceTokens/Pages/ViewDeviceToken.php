<?php

namespace App\Filament\Resources\DeviceTokens\Pages;

use App\Filament\Resources\DeviceTokens\DeviceTokenResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewDeviceToken extends ViewRecord
{
    protected static string $resource = DeviceTokenResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}

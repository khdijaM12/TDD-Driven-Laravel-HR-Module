<?php

namespace App\Filament\Resources\CompanySubscriptionResource\Pages;

use App\Filament\Resources\CompanySubscriptionResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCompanySubscription extends EditRecord
{
    protected static string $resource = CompanySubscriptionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}

<?php

namespace App\Filament\Resources\CompanySubscriptionResource\Pages;

use App\Filament\Resources\CompanySubscriptionResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCompanySubscriptions extends ListRecords
{
    protected static string $resource = CompanySubscriptionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}

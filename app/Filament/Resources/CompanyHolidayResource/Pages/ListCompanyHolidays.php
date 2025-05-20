<?php

namespace App\Filament\Resources\CompanyHolidayResource\Pages;

use App\Filament\Resources\CompanyHolidayResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCompanyHolidays extends ListRecords
{
    protected static string $resource = CompanyHolidayResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}

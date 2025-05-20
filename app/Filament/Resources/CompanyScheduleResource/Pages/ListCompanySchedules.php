<?php

namespace App\Filament\Resources\CompanyScheduleResource\Pages;

use App\Filament\Resources\CompanyScheduleResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCompanySchedules extends ListRecords
{
    protected static string $resource = CompanyScheduleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}

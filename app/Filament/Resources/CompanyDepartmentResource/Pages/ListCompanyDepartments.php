<?php

namespace App\Filament\Resources\CompanyDepartmentResource\Pages;

use App\Filament\Resources\CompanyDepartmentResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCompanyDepartments extends ListRecords
{
    protected static string $resource = CompanyDepartmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}

<?php

namespace App\Filament\Resources\CompanyDepartmentResource\Pages;

use App\Filament\Resources\CompanyDepartmentResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCompanyDepartment extends EditRecord
{
    protected static string $resource = CompanyDepartmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}

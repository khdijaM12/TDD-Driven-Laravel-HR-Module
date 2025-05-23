<?php

namespace App\Filament\Resources\CompanyBranchResource\Pages;

use App\Filament\Resources\CompanyBranchResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCompanyBranch extends EditRecord
{
    protected static string $resource = CompanyBranchResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}

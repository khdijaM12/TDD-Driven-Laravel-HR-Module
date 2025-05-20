<?php

namespace App\Filament\Resources\CompanyJobResource\Pages;

use App\Filament\Resources\CompanyJobResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCompanyJob extends EditRecord
{
    protected static string $resource = CompanyJobResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}

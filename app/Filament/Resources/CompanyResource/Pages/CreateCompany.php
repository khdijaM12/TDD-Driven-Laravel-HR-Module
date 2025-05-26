<?php

namespace App\Filament\Resources\CompanyResource\Pages;

use App\Filament\Resources\CompanyResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Filament\Forms\Components\TextInput;
use App\Filament\Forms\Traits\HandlesFullName;

class CreateCompany extends CreateRecord
{
    use HandlesFullName;

    protected static string $resource = CompanyResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        return $this->mutateFullName($data); 
    }
}

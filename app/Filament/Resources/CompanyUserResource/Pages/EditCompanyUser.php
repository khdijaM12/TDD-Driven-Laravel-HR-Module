<?php

namespace App\Filament\Resources\CompanyUserResource\Pages;

use App\Filament\Resources\CompanyUserResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Notifications\Notification;
use App\Traits\ValidatesCompanySubscription;
use Illuminate\Validation\ValidationException;
use App\Traits\NotifiesWithError;

class EditCompanyUser extends EditRecord
{
    use ValidatesCompanySubscription, NotifiesWithError;

    protected static string $resource = CompanyUserResource::class;

    protected function mutateFormDataBeforeSave(array $data): array
    {
        try {
            $companyId = $data['company_id'] ?? null;

            if (!$companyId) {
                $this->notifyError('Please select a company.');
                return $data;
            }

            $this->validateCompanySubscription($companyId, $this->record->id);
        } catch (ValidationException $e) {
            $this->notifyError($e->getMessage());
        }

        return $data;
    }
}

<?php

namespace App\Filament\Resources\CompanyUserResource\Pages;

use App\Filament\Resources\CompanyUserResource;

use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;
use Illuminate\Validation\ValidationException;
use App\Traits\ValidatesCompanySubscription;
use App\Traits\NotifiesWithError;

class CreateCompanyUser extends CreateRecord
{
    use ValidatesCompanySubscription;

    protected static string $resource = CompanyUserResource::class;

    public function create(bool $isDuplicated = false): void
    {
        try {
            $companyId = $this->data['company_id'] ?? null;

            if (!$companyId) {
                $this->notifyError('Please select a company.');
                return;
            }

            $this->validateCompanySubscription($companyId);

            parent::create($isDuplicated);
        } catch (ValidationException $e) {
            $this->notifyError($e->getMessage());
        }
    }

    private function notifyError(string $message): void
    {
        Notification::make()
            ->title('error')
            ->body($message)
            ->danger()
            ->send();
    }
}

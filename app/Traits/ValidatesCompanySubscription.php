<?php

namespace App\Traits;

use App\Models\Company;
use Illuminate\Validation\ValidationException;

trait ValidatesCompanySubscription
{
    /**
     * Validate the company's subscription and employee count.
     *
     * @param int $companyId The ID of the company to validate.
     * @param int|null $excludingUserId (Optional) User ID to exclude from the count.
     * @throws ValidationException
     */
    protected function validateCompanySubscription(int $companyId, ?int $excludingUserId = null): void
    {
        $company = Company::find($companyId);

        if (!$company) {
            throw ValidationException::withMessages([
                'company_id' => 'The selected company does not exist.',
            ]);
        }

        $subscription = $company->subscriptions()
            ->whereDate('subscribe_start', '<=', now())
            ->whereDate('subscribe_end', '>=', now())
            ->latest('subscribe_end')
            ->first();

        if (!$subscription) {
            throw ValidationException::withMessages([
                'company_id' => 'There is no active subscription for the selected company.',
            ]);
        }

        $query = $company->users();
        if ($excludingUserId) {
            $query->where('id', '!=', $excludingUserId);
        }

        if ($query->count() >= $subscription->number_employees) {
            throw ValidationException::withMessages([
                'company_id' => 'The number of employees exceeds the allowed limit for the current subscription.',
            ]);
        }
    }
}

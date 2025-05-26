<?php

use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\CompanySubscription;
use App\Models\Company;


uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

test('fillable properties', function () {
    $subscription = new CompanySubscription();

    $expected = ['company_id', 'subscribe_start', 'subscribe_end', 'number_employees'];

    expect($subscription->getFillable())->toEqual($expected);
});

test('company relationship', function () {
    $subscription = new CompanySubscription();
    expect($subscription->company())->toBeInstanceOf(BelongsTo::class);
});

test('subscription end date must be after start date', function () {
    $this->expectException(ValidationException::class);

    $company = Company::factory()->create();

    CompanySubscription::create([
        'company_id' => $company->id,
        'subscribe_start' => '2025-01-10',
        'subscribe_end' => '2025-01-01',
        'number_employees' => 10
    ]);
});
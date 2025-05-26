<?php

use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\CompanySchedule;
use App\Models\Company;


uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

test('fillable properties', function () {
    $schedule = new CompanySchedule();

    $expected = ['company_id', 'slug', 'weekend_days', 'check_in_time', 'check_out_time'];

    expect($schedule->getFillable())->toEqual($expected);
});

test('company relationship', function () {
    $schedule = new CompanySchedule();
    expect($schedule->company())->toBeInstanceOf(BelongsTo::class);
});

test('slug must be unique', function () {
    $this->expectException(ValidationException::class);

    $company = Company::factory()->create();

    CompanySchedule::factory()->create([
        'company_id' => $company->id,
        'slug' => 'test-schedule',
    ]);

    CompanySchedule::factory()->create([
        'company_id' => $company->id,
        'slug' => 'test-schedule',
    ]);
});
<?php

use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\CompanyHoliday;
use App\Models\Company;
use App\Models\CompanyBranch;


uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

test('fillable properties', function () {
    $holiday = new CompanyHoliday();

    $expected = ['company_id', 'occasion', 'date_from', 'date_to', 'branch_id'];

    expect($holiday->getFillable())->toEqual($expected);
});

test('company relationship', function () {
    $holiday = new CompanyHoliday();
    expect($holiday->company())->toBeInstanceOf(BelongsTo::class);
});

test('branch relationship', function () {
    $holiday = new CompanyHoliday();
    expect($holiday->branch())->toBeInstanceOf(BelongsTo::class);
});

test('holiday end date must be after or equal to start date', function () {
    $this->expectException(ValidationException::class);

    $company = Company::factory()->create();

    CompanyHoliday::create([
        'company_id' => $company->id,
        'occasion' => 'Test Holiday',
        'date_from' => '2023-01-10',
        'date_to' => '2023-01-01'
    ]);
});

test('valid holiday dates', function () {
    $company = Company::factory()->create();

    $holiday = CompanyHoliday::create([
        'company_id' => $company->id,
        'occasion' => 'Test Holiday',
        'date_from' => '2023-01-01',
        'date_to' => '2023-01-10'
    ]);

    $this->assertDatabaseHas('company_holidays', [
        'id' => $holiday->id,
        'occasion' => 'Test Holiday'
    ]);
});
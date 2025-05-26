<?php

use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Company;
use App\Models\CompanyJob;


uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

test('fillable properties', function () {
    $company = new Company();

    $expected = ['name_en', 'name_ar', 'logo', 'website', 'status'];

    expect($company->getFillable())->toEqual($expected);
});

test('company has many jobs relationship', function () {
    $company = new Company();

    expect($company->jobs())->toBeInstanceOf(HasMany::class);
});
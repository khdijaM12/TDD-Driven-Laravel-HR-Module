<?php

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\CompanyDepartment;


uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

test('fillable properties', function () {
    $department = new CompanyDepartment();

    $expected = ['company_id', 'name_en', 'name_ar'];

    expect($department->getFillable())->toEqual($expected);
});

test('company relationship', function () {
    $department = new CompanyDepartment();
    expect($department->company())->toBeInstanceOf(BelongsTo::class);
});
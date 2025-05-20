<?php

use App\Filament\Resources\CompanyHolidayResource;
use App\Models\CompanyHoliday;
use App\Models\Company;
use App\Models\CompanyBranch;
use function Pest\Livewire\livewire;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('can render create form', function () {
    livewire(CompanyHolidayResource\Pages\CreateCompanyHoliday::class)
        ->assertFormExists();
});

it('has correct form fields', function () {
    livewire(CompanyHolidayResource\Pages\CreateCompanyHoliday::class)
        ->assertFormFieldExists('company_id')
        ->assertFormFieldExists('branch_id')
        ->assertFormFieldExists('occasion')
        ->assertFormFieldExists('date_from')
        ->assertFormFieldExists('date_to');
});

it('can validate form input', function () {
    livewire(CompanyHolidayResource\Pages\CreateCompanyHoliday::class)
        ->fillForm([
            'company_id' => null,
            'branch_id' => null,
            'occasion' => null,
            'date_from' => null,
            'date_to' => null,
        ])
        ->call('create')
        ->assertHasFormErrors([
            'company_id' => 'required',
            'occasion' => 'required',
            'date_from' => 'required',
            'date_to' => 'required',
        ]);
});

it('can list company holidays', function () {
    $company = Company::factory()->create();
    $branch = CompanyBranch::factory()->create(['company_id' => $company->id]);
    $holidays = CompanyHoliday::factory()->count(3)->create([
        'company_id' => $company->id,
        'branch_id' => $branch->id,
    ]);

    livewire(CompanyHolidayResource\Pages\ListCompanyHolidays::class)
        ->assertCanSeeTableRecords($holidays);
});

it('can filter holidays by company', function () {
    $company1 = Company::factory()->create();
    $company2 = Company::factory()->create();

    $holiday1 = CompanyHoliday::factory()->create(['company_id' => $company1->id]);
    $holiday2 = CompanyHoliday::factory()->create(['company_id' => $company2->id]);

    livewire(CompanyHolidayResource\Pages\ListCompanyHolidays::class)
        ->filterTable('company_id', $company1->id)
        ->assertCanSeeTableRecords([$holiday1])
        ->assertCanNotSeeTableRecords([$holiday2]);
});

it('can search by occasion name', function () {
    $company = Company::factory()->create();
    $branch = CompanyBranch::factory()->create(['company_id' => $company->id]);

    $holiday1 = CompanyHoliday::factory()->create([
        'company_id' => $company->id,
        'branch_id' => $branch->id,
        'occasion' => 'Eid Al-Fitr',
    ]);
    $holiday2 = CompanyHoliday::factory()->create([
        'company_id' => $company->id,
        'branch_id' => $branch->id,
        'occasion' => 'New Year',
    ]);

    livewire(CompanyHolidayResource\Pages\ListCompanyHolidays::class)
        ->searchTable('Eid')
        ->assertCanSeeTableRecords([$holiday1])
        ->assertCanNotSeeTableRecords([$holiday2]);
});

it('can delete a company holiday', function () {
    $company = Company::factory()->create();
    $branch = CompanyBranch::factory()->create(['company_id' => $company->id]);
    $holiday = CompanyHoliday::factory()->create([
        'company_id' => $company->id,
        'branch_id' => $branch->id,
    ]);

    livewire(CompanyHolidayResource\Pages\EditCompanyHoliday::class, [
        'record' => $holiday->id,
    ])
        ->callAction('delete')
        ->assertHasNoActionErrors();

    expect($holiday->fresh())->toBeNull();
});

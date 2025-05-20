<?php

use App\Filament\Resources\CompanyDepartmentResource;
use App\Models\Company;
use App\Models\CompanyDepartment;
use function Pest\Livewire\livewire;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('can render create form', function () {
    livewire(CompanyDepartmentResource\Pages\CreateCompanyDepartment::class)
        ->assertFormExists();
});

it('has correct form fields', function () {
    livewire(CompanyDepartmentResource\Pages\CreateCompanyDepartment::class)
        ->assertFormFieldExists('company_id')
        ->assertFormFieldExists('name_en')
        ->assertFormFieldExists('name_ar');
});

it('can validate form input', function () {
    livewire(CompanyDepartmentResource\Pages\CreateCompanyDepartment::class)
        ->fillForm([
            'company_id' => null,
            'name_en' => null,
            'name_ar' => null,
        ])
        ->call('create')
        ->assertHasFormErrors([
            'company_id' => 'required',
            'name_en' => 'required',
            'name_ar' => 'required',
        ]);
});

// it('can create a new company department', function () {
//     $company = Company::factory()->create();

//     livewire(CompanyDepartmentResource\Pages\CreateCompanyDepartment::class)
//         ->fillForm([
//             'company_id' => $company->id,
//             'name_en' => 'HR Department',
//             'name_ar' => 'قسم الموارد البشرية',
//         ])
//         ->call('create')
//         ->assertHasNoFormErrors();

//     $this->assertDatabaseHas(CompanyDepartment::class, [
//         'company_id' => $company->id,
//         'name_en' => 'HR Department',
//         'name_ar' => 'قسم الموارد البشرية',
//     ]);
// });

it('can list company departments', function () {
    $company = Company::factory()->create();
    $departments = CompanyDepartment::factory()
        ->count(3)
        ->create(['company_id' => $company->id]);

    livewire(CompanyDepartmentResource\Pages\ListCompanyDepartments::class)
        ->assertCanSeeTableRecords($departments);
});

it('can filter departments by company', function () {
    $company1 = Company::factory()->create();
    $company2 = Company::factory()->create();

    $dep1 = CompanyDepartment::factory()->create(['company_id' => $company1->id]);
    $dep2 = CompanyDepartment::factory()->create(['company_id' => $company2->id]);

    livewire(CompanyDepartmentResource\Pages\ListCompanyDepartments::class)
        ->filterTable('company_id', $company1->id)
        ->assertCanSeeTableRecords([$dep1])
        ->assertCanNotSeeTableRecords([$dep2]);
});

it('can search departments by english name', function () {
    $company = Company::factory()->create();
    $dep1 = CompanyDepartment::factory()->create([
        'company_id' => $company->id,
        'name_en' => 'Finance'
    ]);
    $dep2 = CompanyDepartment::factory()->create([
        'company_id' => $company->id,
        'name_en' => 'IT'
    ]);

    livewire(CompanyDepartmentResource\Pages\ListCompanyDepartments::class)
        ->searchTable('Finance')
        ->assertCanSeeTableRecords([$dep1])
        ->assertCanNotSeeTableRecords([$dep2]);
});

it('can render edit form with correct data', function () {
    $company = Company::factory()->create();
    $dep = CompanyDepartment::factory()->create(['company_id' => $company->id]);

    livewire(CompanyDepartmentResource\Pages\EditCompanyDepartment::class, [
        'record' => $dep->id,
    ])
        ->assertFormSet([
            'company_id' => $dep->company_id,
            'name_en' => $dep->name_en,
            'name_ar' => $dep->name_ar,
        ]);
});

// it('can update a company department', function () {
//     $company = Company::factory()->create();
//     $newCompany = Company::factory()->create();
//     $dep = CompanyDepartment::factory()->create(['company_id' => $company->id]);

//     livewire(CompanyDepartmentResource\Pages\EditCompanyDepartment::class, [
//         'record' => $dep->id,
//     ])
//         ->fillForm([
//             'company_id' => $newCompany->id,
//             'name_en' => 'Updated EN',
//             'name_ar' => 'تم التحديث',
//         ])
//         ->call('save')
//         ->assertHasNoFormErrors();

//     $this->assertDatabaseHas(CompanyDepartment::class, [
//         'id' => $dep->id,
//         'company_id' => $newCompany->id,
//         'name_en' => 'Updated EN',
//         'name_ar' => 'تم التحديث',
//     ]);
// });

it('can delete a company department', function () {
    $company = Company::factory()->create();
    $dep = CompanyDepartment::factory()->create(['company_id' => $company->id]);

    livewire(CompanyDepartmentResource\Pages\EditCompanyDepartment::class, [
        'record' => $dep->id,
    ])
        ->callAction('delete')
        ->assertHasNoActionErrors();

    $this->assertModelMissing($dep);
});

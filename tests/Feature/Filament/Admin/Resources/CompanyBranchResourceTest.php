<?php

use App\Filament\Resources\CompanyBranchResource;
use App\Models\Company;
use App\Models\CompanyBranch;
use Illuminate\Foundation\Testing\RefreshDatabase;
use function Pest\Livewire\livewire;

uses(RefreshDatabase::class);

it('can render create form', function () {
    livewire(CompanyBranchResource\Pages\CreateCompanyBranch::class)
        ->assertFormExists();
});

it('has correct form fields', function () {
    livewire(CompanyBranchResource\Pages\CreateCompanyBranch::class)
        ->assertFormFieldExists('company_id')
        ->assertFormFieldExists('name_en')
        ->assertFormFieldExists('name_ar');
});

it('can validate form input', function () {
    livewire(CompanyBranchResource\Pages\CreateCompanyBranch::class)
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

// it('can create a new company branch', function () {
//     $company = Company::factory()->create();

//     livewire(CompanyBranchResource\Pages\CreateCompanyBranch::class)
//         ->fillForm([
//             'company_id' => $company->id,
//             'name_en' => 'Main Branch',
//             'name_ar' => 'الفرع الرئيسي',
//         ])
//         ->call('create')
//         ->assertHasNoFormErrors();

//     expect(CompanyBranch::where('name_en', 'Main Branch')->exists())->toBeTrue();
// });

it('can list company branches', function () {
    $company = Company::factory()->create();
    $branches = CompanyBranch::factory()->count(3)->create(['company_id' => $company->id]);

    livewire(CompanyBranchResource\Pages\ListCompanyBranches::class)
        ->assertCanSeeTableRecords($branches);
});

it('can filter branches by company', function () {
    $company1 = Company::factory()->create();
    $company2 = Company::factory()->create();

    $branch1 = CompanyBranch::factory()->create(['company_id' => $company1->id]);
    $branch2 = CompanyBranch::factory()->create(['company_id' => $company2->id]);

    livewire(CompanyBranchResource\Pages\ListCompanyBranches::class)
        ->filterTable('company_id', $company1->id)
        ->assertCanSeeTableRecords([$branch1])
        ->assertCanNotSeeTableRecords([$branch2]);
});

it('can search branches by english name', function () {
    $company = Company::factory()->create();
    $branch1 = CompanyBranch::factory()->create(['company_id' => $company->id, 'name_en' => 'Finance']);
    $branch2 = CompanyBranch::factory()->create(['company_id' => $company->id, 'name_en' => 'IT']);

    livewire(CompanyBranchResource\Pages\ListCompanyBranches::class)
        ->searchTable('Finance')
        ->assertCanSeeTableRecords([$branch1])
        ->assertCanNotSeeTableRecords([$branch2]);
});

it('can render edit form with correct data', function () {
    $company = Company::factory()->create();
    $branch = CompanyBranch::factory()->create(['company_id' => $company->id]);

    livewire(CompanyBranchResource\Pages\EditCompanyBranch::class, [
        'record' => $branch->id,
    ])
        ->assertFormSet([
            'company_id' => $branch->company_id,
            'name_en' => $branch->name_en,
            'name_ar' => $branch->name_ar,
        ]);
});

// it('can update a company branch', function () {
//     $company1 = Company::factory()->create();
//     $company2 = Company::factory()->create();
//     $branch = CompanyBranch::factory()->create(['company_id' => $company1->id]);

//     livewire(CompanyBranchResource\Pages\EditCompanyBranch::class, [
//         'record' => $branch->id,
//     ])
//         ->fillForm([
//             'company_id' => $company2->id,
//             'name_en' => 'Updated Branch EN',
//             'name_ar' => 'فرع محدث',
//         ])
//         ->call('save')
//         ->assertHasNoFormErrors();

//     expect(CompanyBranch::find($branch->id))->toMatchArray([
//         'company_id' => $company2->id,
//         'name_en' => 'Updated Branch EN',
//         'name_ar' => 'فرع محدث',
//     ]);
// });

it('can delete a company branch', function () {
    $company = Company::factory()->create();
    $branch = CompanyBranch::factory()->create(['company_id' => $company->id]);

    livewire(CompanyBranchResource\Pages\EditCompanyBranch::class, [
        'record' => $branch->id,
    ])
        ->callAction('delete')
        ->assertHasNoActionErrors();

    expect(CompanyBranch::find($branch->id))->toBeNull();
});

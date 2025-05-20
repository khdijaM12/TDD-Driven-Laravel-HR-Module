<?php

use App\Filament\Resources\CompanyJobResource;
use App\Models\Company;
use App\Models\CompanyJob;
use function Pest\Livewire\livewire;

beforeEach(function () {
    CompanyJob::factory()->definition(); 
});

it('can render create form', function () {
    livewire(CompanyJobResource\Pages\CreateCompanyJob::class)
        ->assertFormExists();
});

it('has correct form fields', function () {
    livewire(CompanyJobResource\Pages\CreateCompanyJob::class)
        ->assertFormFieldExists('company_id')
        ->assertFormFieldExists('name_en')
        ->assertFormFieldExists('name_ar');
});

it('can validate form input', function () {
    livewire(CompanyJobResource\Pages\CreateCompanyJob::class)
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

// it('can create a new company job', function () {
//     $company = Company::factory()->create();
    
//     livewire(CompanyJobResource\Pages\CreateCompanyJob::class)
//         ->fillForm([
//             'company_id' => $company->id,
//             'name_en' => 'Test Job EN',
//             'name_ar' => 'Test Job AR',
//         ])
//         ->call('create')
//         ->assertHasNoFormErrors();
    
//     $this->assertDatabaseHas(CompanyJob::class, [
//         'company_id' => $company->id,
//         'name_en' => 'Test Job EN',
//         'name_ar' => 'Test Job AR',
//     ]);
// });

it('can list company jobs', function () {
    $company = Company::factory()->create();
    $jobs = CompanyJob::factory()
        ->count(3)
        ->create(['company_id' => $company->id]);
    
    livewire(CompanyJobResource\Pages\ListCompanyJobs::class)
        ->assertCanSeeTableRecords($jobs);
});

it('can filter company jobs by company', function () {
    $company1 = Company::factory()->create();
    $company2 = Company::factory()->create();
    
    $job1 = CompanyJob::factory()->create(['company_id' => $company1->id]);
    $job2 = CompanyJob::factory()->create(['company_id' => $company2->id]);
    
    livewire(CompanyJobResource\Pages\ListCompanyJobs::class)
        ->filterTable('company_id', $company1->id)
        ->assertCanSeeTableRecords([$job1])
        ->assertCanNotSeeTableRecords([$job2]);
});

it('can search company jobs by english name', function () {
    $company = Company::factory()->create();
    $job1 = CompanyJob::factory()->create([
        'company_id' => $company->id,
        'name_en' => 'Developer'
    ]);
    $job2 = CompanyJob::factory()->create([
        'company_id' => $company->id,
        'name_en' => 'Designer'
    ]);
    
    livewire(CompanyJobResource\Pages\ListCompanyJobs::class)
        ->searchTable('Developer')
        ->assertCanSeeTableRecords([$job1])
        ->assertCanNotSeeTableRecords([$job2]);
});

it('can render edit form with correct data', function () {
    $company = Company::factory()->create();
    $job = CompanyJob::factory()->create(['company_id' => $company->id]);
    
    livewire(CompanyJobResource\Pages\EditCompanyJob::class, [
        'record' => $job->id,
    ])
        ->assertFormSet([
            'company_id' => $job->company_id,
            'name_en' => $job->name_en,
            'name_ar' => $job->name_ar,
        ]);
});

// it('can update a company job', function () {
//     $company = Company::factory()->create();
//     $newCompany = Company::factory()->create();
//     $job = CompanyJob::factory()->create(['company_id' => $company->id]);
    
//     livewire(CompanyJobResource\Pages\EditCompanyJob::class, [
//         'record' => $job->id,
//     ])
//         ->fillForm([
//             'company_id' => $newCompany->id,
//             'name_en' => 'Updated Job EN',
//             'name_ar' => 'Updated Job AR',
//         ])
//         ->call('save')
//         ->assertHasNoFormErrors();
    
//     $this->assertDatabaseHas(CompanyJob::class, [
//         'id' => $job->id,
//         'company_id' => $newCompany->id,
//         'name_en' => 'Updated Job EN',
//         'name_ar' => 'Updated Job AR',
//     ]);
// });

it('can delete a company job', function () {
    $company = Company::factory()->create();
    $job = CompanyJob::factory()->create(['company_id' => $company->id]);
    
    livewire(CompanyJobResource\Pages\EditCompanyJob::class, [
        'record' => $job->id,
    ])
        ->callAction('delete')
        ->assertHasNoActionErrors();
    
    $this->assertModelMissing($job);
});
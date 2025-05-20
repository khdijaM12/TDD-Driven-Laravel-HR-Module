<?php

namespace Tests\Feature\Filament\Admin\Resources;

use App\Filament\Resources\CompanyResource;
use App\Models\Company;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use function Pest\Livewire\livewire;

beforeEach(function () {
    $this->actingAs(\App\Models\User::factory()->create());
});

it('can render company resource index page', function () {
    $this->get(CompanyResource::getUrl())->assertSuccessful();
});

it('can render create company page', function () {
    $this->get(CompanyResource::getUrl('create'))->assertSuccessful();
});

it('can list companies in table with all columns', function () {
    $company = Company::factory()->create();

    livewire(CompanyResource\Pages\ListCompanies::class)
        ->assertCanSeeTableRecords([$company])
        ->assertTableColumnExists('name_en')
        ->assertTableColumnExists('name_ar')
        ->assertTableColumnExists('logo')
        ->assertTableColumnExists('website')
        ->assertTableColumnExists('status')
        ->assertTableColumnExists('created_at');
});

it('can render create form with all fields', function () {
    livewire(CompanyResource\Pages\CreateCompany::class)
        ->assertFormExists()
        ->assertFormFieldExists('name_en')
        ->assertFormFieldExists('name_ar')
        ->assertFormFieldExists('logo')
        ->assertFormFieldExists('website')
        ->assertFormFieldExists('status');
});

it('can validate company creation form with all fields', function () {
    livewire(CompanyResource\Pages\CreateCompany::class)
        ->fillForm([
            'name_en' => null,
            'name_ar' => null,
            'status' => null,
            'website' => null,
        ])
        ->call('create')
        ->assertHasFormErrors([
            'name_en' => 'required',
            'name_ar' => 'required',
        ]);
});

// it('can create company with all fields', function () {
//     Storage::fake('public');
//     $logo = UploadedFile::fake()->image('logo.jpg');

//     $newData = [
//         'name_en' => 'Test Company',
//         'name_ar' => 'شركة تجريبية',
//         'status' => 'active',
//         'website' => 'https://example.com',
//         'logo' => $logo,
//     ];

//     livewire(CompanyResource\Pages\CreateCompany::class)
//         ->fillForm($newData)
//         ->call('create')
//         ->assertHasNoFormErrors();

//     unset($newData['logo']); // Remove the file before checking database
//     $this->assertDatabaseHas(Company::class, $newData);

//     $company = Company::where('name_en', 'Test Company')->first();
//     Storage::disk('public')->assertExists($company->logo);
// });

it('can render edit form with company data', function () {
    $company = Company::factory()->create();

    livewire(CompanyResource\Pages\EditCompany::class, [
        'record' => $company->id,
    ])
        ->assertFormSet([
            'name_en' => $company->name_en,
            'name_ar' => $company->name_ar,
            'status' => $company->status,
            'website' => $company->website,
        ]);
});

// it('can update company with all fields', function () {
//     $company = Company::factory()->create();
//     Storage::fake('public');
//     $newLogo = UploadedFile::fake()->image('new-logo.jpg');

//     $newData = [
//         'name_en' => 'Updated Name',
//         'name_ar' => 'اسم محدث',
//         'status' => 'inactive',
//         'website' => 'https://updated.com',
//         'logo' => $newLogo,
//     ];

//     livewire(CompanyResource\Pages\EditCompany::class, [
//         'record' => $company->id,
//     ])
//         ->fillForm($newData)
//         ->call('save')
//         ->assertHasNoFormErrors();

//     unset($newData['logo']); // Remove the file before checking database
//     $this->assertDatabaseHas(Company::class, array_merge(['id' => $company->id], $newData));
    
//     $updatedCompany = Company::find($company->id);
//     Storage::disk('public')->assertExists($updatedCompany->logo);
// });

it('can filter companies by status', function () {
    $activeCompanies = Company::factory()->count(3)->create(['status' => 'active']);
    $inactiveCompanies = Company::factory()->count(2)->create(['status' => 'inactive']);

    livewire(CompanyResource\Pages\ListCompanies::class)
        ->filterTable('status', 'active')
        ->assertCanSeeTableRecords($activeCompanies)
        ->assertCanNotSeeTableRecords($inactiveCompanies)
        ->filterTable('status', 'inactive')
        ->assertCanSeeTableRecords($inactiveCompanies)
        ->assertCanNotSeeTableRecords($activeCompanies);
});

it('can delete company', function () {
    $company = Company::factory()->create();

    livewire(CompanyResource\Pages\EditCompany::class, [
        'record' => $company->id,
    ])
        ->callAction('delete')
        ->assertHasNoActionErrors();

    $this->assertModelMissing($company);
});

it('can search companies by name_en', function () {
    $company1 = Company::factory()->create(['name_en' => 'Unique Name Company']);
    $company2 = Company::factory()->create(['name_en' => 'Another Company']);

    livewire(CompanyResource\Pages\ListCompanies::class)
        ->searchTable('Unique Name')
        ->assertCanSeeTableRecords([$company1])
        ->assertCanNotSeeTableRecords([$company2]);
});

it('can search companies by name_ar', function () {
    $company1 = Company::factory()->create(['name_ar' => 'شركة فريدة']);
    $company2 = Company::factory()->create(['name_ar' => 'شركة أخرى']);

    livewire(CompanyResource\Pages\ListCompanies::class)
        ->searchTable('فريدة')
        ->assertCanSeeTableRecords([$company1])
        ->assertCanNotSeeTableRecords([$company2]);
});
<?php

use App\Filament\Resources\CompanySubscriptionResource;
use App\Models\Company;
use App\Models\CompanySubscription;
use function Pest\Livewire\livewire;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('can render create form', function () {
    livewire(CompanySubscriptionResource\Pages\CreateCompanySubscription::class)
        ->assertFormExists();
});

it('has correct form fields', function () {
    livewire(CompanySubscriptionResource\Pages\CreateCompanySubscription::class)
        ->assertFormFieldExists('company_id')
        ->assertFormFieldExists('subscribe_start')
        ->assertFormFieldExists('subscribe_end')
        ->assertFormFieldExists('number_employees');
});

it('can validate form input', function () {
    livewire(CompanySubscriptionResource\Pages\CreateCompanySubscription::class)
        ->fillForm([
            'company_id' => null,
            'subscribe_start' => null,
            'subscribe_end' => null,
            'number_employees' => null,
        ])
        ->call('create')
        ->assertHasFormErrors([
            'company_id' => 'required',
            'subscribe_start' => 'required',
            'subscribe_end' => 'required',
            'number_employees' => 'required',
        ]);
});

// it('can create a new company subscription', function () {
//     $company = Company::factory()->create();
//
//     livewire(CompanySubscriptionResource\Pages\CreateCompanySubscription::class)
//         ->fillForm([
//             'company_id' => $company->id,
//             'subscribe_start' => now()->toDateString(),
//             'subscribe_end' => now()->addMonth()->toDateString(),
//             'number_employees' => 10,
//         ])
//         ->call('create')
//         ->assertHasNoFormErrors();
//
//     $this->assertDatabaseHas(CompanySubscription::class, [
//         'company_id' => $company->id,
//         'number_employees' => 10,
//     ]);
// });

it('can list company subscriptions', function () {
    $company = Company::factory()->create();
    $subscriptions = CompanySubscription::factory()
        ->count(3)
        ->create(['company_id' => $company->id]);

    livewire(CompanySubscriptionResource\Pages\ListCompanySubscriptions::class)
        ->assertCanSeeTableRecords($subscriptions);
});

it('can filter subscriptions by company', function () {
    $company1 = Company::factory()->create();
    $company2 = Company::factory()->create();

    $sub1 = CompanySubscription::factory()->create(['company_id' => $company1->id]);
    $sub2 = CompanySubscription::factory()->create(['company_id' => $company2->id]);

    livewire(CompanySubscriptionResource\Pages\ListCompanySubscriptions::class)
        ->filterTable('company_id', $company1->id)
        ->assertCanSeeTableRecords([$sub1])
        ->assertCanNotSeeTableRecords([$sub2]);
});

it('can search by company english name', function () {
    $company1 = Company::factory()->create(['name_en' => 'Alpha Co']);
    $company2 = Company::factory()->create(['name_en' => 'Beta Inc']);

    $sub1 = CompanySubscription::factory()->create(['company_id' => $company1->id]);
    $sub2 = CompanySubscription::factory()->create(['company_id' => $company2->id]);

    livewire(CompanySubscriptionResource\Pages\ListCompanySubscriptions::class)
        ->searchTable('Alpha')
        ->assertCanSeeTableRecords([$sub1])
        ->assertCanNotSeeTableRecords([$sub2]);
});

it('can render edit form with correct data', function () {
    $company = Company::factory()->create();
    $sub = CompanySubscription::factory()->create(['company_id' => $company->id]);

    livewire(CompanySubscriptionResource\Pages\EditCompanySubscription::class, [
        'record' => $sub->id,
    ])
        ->assertFormSet([
            'company_id' => $sub->company_id,
            'subscribe_start' => $sub->subscribe_start->toDateString(),
            'subscribe_end' => $sub->subscribe_end->toDateString(),
            'number_employees' => $sub->number_employees,
        ]);

});

// it('can update a company subscription', function () {
//     $company = Company::factory()->create();
//     $newCompany = Company::factory()->create();
//     $sub = CompanySubscription::factory()->create(['company_id' => $company->id]);
//
//     livewire(CompanySubscriptionResource\Pages\EditCompanySubscription::class, [
//         'record' => $sub->id,
//     ])
//         ->fillForm([
//             'company_id' => $newCompany->id,
//             'subscribe_start' => now()->toDateString(),
//             'subscribe_end' => now()->addMonth()->toDateString(),
//             'number_employees' => 20,
//         ])
//         ->call('save')
//         ->assertHasNoFormErrors();
//
//     $this->assertDatabaseHas(CompanySubscription::class, [
//         'id' => $sub->id,
//         'company_id' => $newCompany->id,
//         'number_employees' => 20,
//     ]);
// });

it('can delete a company subscription', function () {
    $company = Company::factory()->create();
    $sub = CompanySubscription::factory()->create(['company_id' => $company->id]);

    livewire(CompanySubscriptionResource\Pages\EditCompanySubscription::class, [
        'record' => $sub->id,
    ])
        ->callAction('delete')
        ->assertHasNoActionErrors();

    $this->assertModelMissing($sub);
});

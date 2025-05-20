<?php

use App\Filament\Resources\CompanyScheduleResource;
use App\Models\Company;
use App\Models\CompanySchedule;
use function Pest\Livewire\livewire;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('can render create form', function () {
    livewire(CompanyScheduleResource\Pages\CreateCompanySchedule::class)
        ->assertFormExists();
});

it('has correct form fields', function () {
    livewire(CompanyScheduleResource\Pages\CreateCompanySchedule::class)
        ->assertFormFieldExists('company_id')
        ->assertFormFieldExists('slug')
        ->assertFormFieldExists('weekend_days')
        ->assertFormFieldExists('check_in_time')
        ->assertFormFieldExists('check_out_time');
});

it('can validate form input', function () {
    livewire(CompanyScheduleResource\Pages\CreateCompanySchedule::class)
        ->fillForm([
            'company_id' => null,
            'slug' => '',
            'weekend_days' => null,
            'check_in_time' => null,
            'check_out_time' => null,
        ])
        ->call('create')
        ->assertHasFormErrors([
            'company_id' => 'required',
            'slug' => 'required',
            'weekend_days' => 'required',
            'check_in_time' => 'required',
            'check_out_time' => 'required',
        ]);
});

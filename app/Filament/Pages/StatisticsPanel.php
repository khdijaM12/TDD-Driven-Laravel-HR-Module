<?php

namespace App\Filament\Pages;

use App\Models\Company;
use App\Models\CompanyJob;
use App\Models\CompanySubscription;
use Filament\Pages\Page;

class StatisticsPanel extends Page
{
    
    protected static string $view = 'filament.pages.statistics-panel';

    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';
    protected static ?string $navigationLabel = 'control panel';
    protected static ?string $title = 'control panel';

    public $companyCount;
    public $jobCount;
    public $subscriptionCount;

    public function mount(): void
    {
        $this->companyCount = Company::count();
        $this->jobCount = CompanyJob::count();
        $this->subscriptionCount = CompanySubscription::count();
    }
}

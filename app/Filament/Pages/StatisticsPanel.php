<?php

namespace App\Filament\Pages;

use App\Models\Company;
use App\Models\CompanyJob;
use App\Models\CompanySubscription;
use App\Models\CompanyBranch;
use App\Models\CompanyDepartment;
use App\Models\CompanyHoliday;
use Illuminate\Support\Facades\DB;
use Filament\Pages\Page;

class StatisticsPanel extends Page
{
    protected static string $view = 'filament.pages.statistics-panel';

    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';
    protected static ?string $navigationLabel = 'Control Panel';
    protected static ?string $title = 'Control Panel';

    public $companyCount;
    public $activeCompaniesCount;
    public $inactiveCompaniesCount;
    public $jobCount;
    public $subscriptionCount;
    public $branchCount;
    public $departmentCount;
    public $holidayCount;

    public $companyStatusData = [];

    // بيانات إضافية للمخططات الجديدة
    public $jobsPerCompany = [];
    public $subscriptionsOverMonths = [];
    public $departmentsPerCompany = [];

    public function mount(): void
    {
        $this->companyCount = Company::count();
        $this->activeCompaniesCount = Company::where('status', 'active')->count();
        $this->inactiveCompaniesCount = Company::where('status', 'inactive')->count();
        $this->jobCount = CompanyJob::count();
        $this->subscriptionCount = CompanySubscription::count();
        $this->branchCount = CompanyBranch::count();
        $this->departmentCount = CompanyDepartment::count();
        $this->holidayCount = CompanyHoliday::count();

        $this->companyStatusData = [
            'active' => $this->activeCompaniesCount,
            'inactive' => $this->inactiveCompaniesCount,
        ];

        // jobs count grouped by company
        $this->jobsPerCompany = CompanyJob::select('company_id', DB::raw('count(*) as total'))
            ->groupBy('company_id')
            ->with('company:id,name_en')  // eager load company name
            ->get()
            ->map(fn($item) => [
                'company' => Company::find($item->company_id)->name_en ?? 'N/A',
                'total' => $item->total,
            ])
            ->toArray();

        // subscriptions count grouped by month (last 6 months)
        $this->subscriptionsOverMonths = CompanySubscription::select(
            DB::raw("DATE_FORMAT(subscribe_start, '%Y-%m') as month"),
            DB::raw('count(*) as total')
        )
        ->where('subscribe_start', '>=', now()->subMonths(6))
        ->groupBy('month')
        ->orderBy('month')
        ->get()
        ->toArray();

        // departments count grouped by company
        $this->departmentsPerCompany = CompanyDepartment::select('company_id', DB::raw('count(*) as total'))
            ->groupBy('company_id')
            ->get()
            ->map(fn($item) => [
                'company' => Company::find($item->company_id)->name_en ?? 'N/A',
                'total' => $item->total,
            ])
            ->toArray();
    }
}

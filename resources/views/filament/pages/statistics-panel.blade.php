<x-filament::page>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <!-- عدادات عامة -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6 text-center">
            <h2 class="text-lg font-semibold text-gray-700 dark:text-gray-300">Companies Count</h2>
            <p class="text-4xl font-bold text-primary mt-2">{{ $companyCount }}</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6 text-center">
            <h2 class="text-lg font-semibold text-gray-700 dark:text-gray-300">Jobs Count</h2>
            <p class="text-4xl font-bold text-primary mt-2">{{ $jobCount }}</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6 text-center">
            <h2 class="text-lg font-semibold text-gray-700 dark:text-gray-300">Subscriptions Count</h2>
            <p class="text-4xl font-bold text-primary mt-2">{{ $subscriptionCount }}</p>
        </div>
    </div>

    <!-- مخطط دائري لحالة الشركات -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6 mb-8">
        <h3 class="text-xl font-bold text-gray-700 dark:text-gray-300 mb-4 text-center">Companies Status Distribution</h3>
        <div id="companiesStatusChart"></div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6 text-center">
            <h2 class="text-lg font-semibold text-gray-700 dark:text-gray-300">Branches Count</h2>
            <p class="text-4xl font-bold text-primary mt-2">{{ $branchCount }}</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6 text-center">
            <h2 class="text-lg font-semibold text-gray-700 dark:text-gray-300">Departments Count</h2>
            <p class="text-4xl font-bold text-primary mt-2">{{ $departmentCount }}</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6 text-center">
            <h2 class="text-lg font-semibold text-gray-700 dark:text-gray-300">Holidays Count</h2>
            <p class="text-4xl font-bold text-primary mt-2">{{ $holidayCount }}</p>
        </div>
    </div>

    <!-- مخطط عمودي: عدد الوظائف حسب الشركة -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6 mb-8">
        <h3 class="text-xl font-bold text-gray-700 dark:text-gray-300 mb-4 text-center">Jobs Per Company</h3>
        <div id="jobsPerCompanyChart"></div>
    </div>

    <!-- مخطط خطي: الاشتراكات على مدى الأشهر الماضية -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6 mb-8">
        <h3 class="text-xl font-bold text-gray-700 dark:text-gray-300 mb-4 text-center">Subscriptions Over Last 6 Months</h3>
        <div id="subscriptionsOverTimeChart"></div>
    </div>

    <!-- مخطط شريطي: عدد الأقسام حسب الشركة -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6 mb-8">
        <h3 class="text-xl font-bold text-gray-700 dark:text-gray-300 mb-4 text-center">Departments Per Company</h3>
        <div id="departmentsPerCompanyChart"></div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {

            // ألوان المشروع
            const colors = {
                primary: '#243D65',
                danger: '#AAAAAA',
                success: '#243D65',
                secondary: '#5280D6',
            };

            // مخطط دائري لحالة الشركات
            var companiesStatusOptions = {
                chart: { type: 'pie', height: 350 },
                labels: ['Active Companies', 'Inactive Companies'],
                series: [{{ $companyStatusData['active'] }}, {{ $companyStatusData['inactive'] }}],
                colors: [colors.success, colors.danger],
                legend: { position: 'bottom' },
                responsive: [{
                    breakpoint: 480,
                    options: { chart: { width: 300 }, legend: { position: 'bottom' } }
                }]
            };
            var companiesStatusChart = new ApexCharts(document.querySelector("#companiesStatusChart"), companiesStatusOptions);
            companiesStatusChart.render();

            // مخطط عمودي: عدد الوظائف حسب الشركة
            var jobsPerCompanyOptions = {
                chart: { type: 'bar', height: 350 },
                series: [{
                    name: 'Jobs',
                    data: @json(array_column($jobsPerCompany, 'total'))
                }],
                xaxis: {
                    categories: @json(array_column($jobsPerCompany, 'company')),
                    labels: { rotate: -45 }
                },
                colors: [colors.primary],
                plotOptions: {
                    bar: { borderRadius: 4, horizontal: false }
                },
                dataLabels: { enabled: false },
                title: { text: 'Jobs per Company', align: 'center' }
            };
            var jobsPerCompanyChart = new ApexCharts(document.querySelector("#jobsPerCompanyChart"), jobsPerCompanyOptions);
            jobsPerCompanyChart.render();

            // مخطط خطي: الاشتراكات حسب الشهر
            var subscriptionsOverTimeOptions = {
                chart: { type: 'line', height: 350 },
                series: [{
                    name: 'Subscriptions',
                    data: @json(array_column($subscriptionsOverMonths, 'total'))
                }],
                xaxis: {
                    categories: @json(array_column($subscriptionsOverMonths, 'month')),
                },
                colors: [colors.secondary],
                stroke: { curve: 'smooth' },
                dataLabels: { enabled: false },
                title: { text: 'Subscriptions Over Last 6 Months', align: 'center' }
            };
            var subscriptionsOverTimeChart = new ApexCharts(document.querySelector("#subscriptionsOverTimeChart"), subscriptionsOverTimeOptions);
            subscriptionsOverTimeChart.render();

            // مخطط شريطي أفقي: الأقسام حسب الشركة
            var departmentsPerCompanyOptions = {
                chart: { type: 'bar', height: 350 },
                series: [{
                    name: 'Departments',
                    data: @json(array_column($departmentsPerCompany, 'total'))
                }],
                xaxis: {
                    categories: @json(array_column($departmentsPerCompany, 'company')),
                    labels: { rotate: -45 }
                },
                colors: [colors.secondary],
                plotOptions: {
                    bar: { borderRadius: 4, horizontal: true }
                },
                dataLabels: { enabled: false },
                title: { text: 'Departments per Company', align: 'center' }
            };
            var departmentsPerCompanyChart = new ApexCharts(document.querySelector("#departmentsPerCompanyChart"), departmentsPerCompanyOptions);
            departmentsPerCompanyChart.render();
        });
    </script>
</x-filament::page>

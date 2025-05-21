<x-filament::page>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6 flex flex-col items-center justify-center text-center">
            <div class="text-lg font-semibold text-gray-700 dark:text-gray-300">Companies Count</div>
            <div class="text-4xl font-bold text-primary mt-2">{{ $companyCount }}</div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6 flex flex-col items-center justify-center text-center">
            <div class="text-lg font-semibold text-gray-700 dark:text-gray-300">Jobs Count</div>
            <div class="text-4xl font-bold text-primary mt-2">{{ $jobCount }}</div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6 flex flex-col items-center justify-center text-center">
            <div class="text-lg font-semibold text-gray-700 dark:text-gray-300">Subscriptions Count</div>
            <div class="text-4xl font-bold text-primary mt-2">{{ $subscriptionCount }}</div>
        </div>
    </div>
</x-filament::page>

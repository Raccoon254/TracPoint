<div class="max-w-7xl mx-auto py-6 space-y-6">
    <!-- Header Section with Department Selector and Refresh -->
    <div class="flex justify-between items-center">
        <div class="flex items-center space-x-4">
            <select wire:model.live="selectedDepartment" class="mt-1 block w-64 pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm rounded-md">
                <option value="">All Departments</option>
                @foreach($departments as $department)
                    <option value="{{ $department['id'] }}">{{ $department['name'] }}</option>
                @endforeach
            </select>
            <h2 class="text-xl font-semibold text-gray-900">
                Dashboard - {{ $this->selectedDepartmentName }}
            </h2>
        </div>

        <button wire:click="refreshData" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-emerald-600 hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500">
            <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
            </svg>
            Refresh
        </button>
    </div>

    <!-- Main Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Total Assets Card -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100">
                    <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                    </svg>
                </div>
                <div class="ml-4">
                    <h4 class="text-lg font-semibold text-gray-900">Total Assets</h4>
                    <p class="text-3xl font-bold text-gray-900">{{ number_format($stats['total_assets']) }}</p>
                </div>
            </div>
        </div>

        <!-- Total Users Card -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-indigo-100">
                    <svg class="h-6 w-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
                <div class="ml-4">
                    <h4 class="text-lg font-semibold text-gray-900">Total Users</h4>
                    <p class="text-3xl font-bold text-gray-900">{{ number_format($stats['total_users']) }}</p>
                </div>
            </div>
        </div>

        <!-- Asset Value Card -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100">
                    <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div class="ml-4">
                    <h4 class="text-lg font-semibold text-gray-900">Asset Value</h4>
                    <p class="text-3xl font-bold text-gray-900">${{ number_format($stats['asset_value'], 2) }}</p>
                </div>
            </div>
        </div>

        <!-- Maintenance Due Card -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-red-100">
                    <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div class="ml-4">
                    <h4 class="text-lg font-semibold text-gray-900">Maintenance Due</h4>
                    <p class="text-3xl font-bold text-gray-900">{{ number_format($stats['maintenance_due']) }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Asset Utilization and Maintenance Alerts Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Asset Utilization Chart -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Asset Utilization</h3>
            <div class="h-64">
                <div class="grid grid-cols-4 gap-4 h-full">
                    @php
                        $totalAssets = array_sum($stats['asset_utilization']);
                    @endphp
                    @foreach($stats['asset_utilization'] as $status => $count)
                        <div class="flex flex-col">
                            <div class="flex-1 bg-emerald-50 rounded-t-lg relative">
                                <div class="absolute bottom-0 w-full bg-emerald-500 rounded-b-lg transition-all duration-500"
                                     style="height: {{ $totalAssets > 0 ? ($count / $totalAssets * 100) : 0 }}%">
                                </div>
                            </div>
                            <div class="text-center mt-2">
                                <span class="text-sm font-medium text-gray-600">{{ str_replace('_', ' ', ucfirst($status)) }}</span>
                                <span class="block text-lg font-semibold text-gray-900">{{ number_format($count) }}</span>
                                @if($totalAssets > 0)
                                    <span class="text-sm text-gray-500">{{ round(($count / $totalAssets) * 100, 1) }}%</span>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Maintenance Alerts -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Maintenance Alerts</h3>
            <div class="space-y-4 max-h-64 overflow-y-auto">
                @forelse($maintenanceAlerts as $alert)
                    <div class="flex items-center p-4 bg-red-50 rounded-lg">
                        <div class="flex-1">
                            <div class="flex justify-between items-start">
                                <p class="text-sm font-medium text-red-900">
                                    {{ $alert->asset->name }}
                                </p>
                                <span class="text-xs text-red-700 bg-red-100 px-2 py-1 rounded-full">
                                    {{ $alert->asset->department->name }}
                                </span>
                            </div>
                            <p class="text-sm text-red-700 mt-1">
                                Due {{ \Carbon\Carbon::parse($alert->next_maintenance_date)->diffForHumans() }}
                            </p>
                        </div>
                    </div>
                @empty
                    <div class="text-center text-gray-500 py-4">
                        No maintenance alerts at this time
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Recent Activities -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Recent Activities</h3>
        <div class="space-y-4 max-h-96 overflow-y-auto">
            @forelse($recentActivities as $activity)
                <div class="flex items-center p-4 bg-gray-50 rounded-lg">
                    <div class="flex-1">
                        <div class="flex justify-between items-start">
                            <p class="text-sm font-medium text-gray-900">
                                {{ $activity->asset_name }}
                                <span class="text-gray-600">was {{ str_replace('_', ' ', $activity->status) }} to</span>
                                {{ $activity->user_name }}
                            </p>
                            <span class="text-xs text-gray-500 bg-gray-100 px-2 py-1 rounded-full">
                                {{ $activity->department_name }}
                            </span>
                        </div>
                        <p class="text-sm text-gray-500 mt-1">
                            {{ \Carbon\Carbon::parse($activity->updated_at)->diffForHumans() }}
                        </p>
                    </div>
                </div>
            @empty
                <div class="text-center text-gray-500 py-4">
                    No recent activities
                </div>
            @endforelse
        </div>
    </div>

    <!-- Loading State -->
    <div wire:loading.flex class="fixed inset-0 bg-black bg-opacity-25 items-center justify-center z-50">
        <div class="bg-white p-4 rounded-lg shadow-lg">
            <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-emerald-500 mx-auto"></div>
            <p class="text-gray-500 mt-2">Loading...</p>
        </div>
    </div>
</div>

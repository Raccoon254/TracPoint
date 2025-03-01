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
                Auditor Dashboard - {{ $this->selectedDepartmentName }}
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
        <!-- Completed Audits Card -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100">
                    <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <h4 class="text-lg font-semibold text-gray-900">Completed Audits</h4>
                    <p class="text-3xl font-bold text-gray-900">{{ number_format($stats['completed_audits']) }}</p>
                </div>
            </div>
        </div>

        <!-- Pending Audits Card -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-orange-100">
                    <svg class="h-6 w-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <h4 class="text-lg font-semibold text-gray-900">Pending Audits</h4>
                    <p class="text-3xl font-bold text-gray-900">{{ number_format($stats['pending_audits']) }}</p>
                </div>
            </div>
        </div>

        <!-- This Week's Audits Card -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100">
                    <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
                <div class="ml-4">
                    <h4 class="text-lg font-semibold text-gray-900">This Week</h4>
                    <p class="text-3xl font-bold text-gray-900">{{ number_format($stats['this_week_audits']) }}</p>
                </div>
            </div>
        </div>

        <!-- Discrepancies Card -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-red-100">
                    <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                <div class="ml-4">
                    <h4 class="text-lg font-semibold text-gray-900">Discrepancies</h4>
                    <p class="text-3xl font-bold text-gray-900">{{ number_format($stats['audits_with_discrepancies']) }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Secondary Stats and Charts Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent Audits -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Recent Audits</h3>
            <div class="space-y-4 max-h-64 overflow-y-auto">
                @forelse($recentAudits as $audit)
                    <div class="flex items-center p-4 bg-gray-50 rounded-lg">
                        <div class="flex-1">
                            <div class="flex justify-between items-start">
                                <p class="text-sm font-medium text-gray-900">
                                    {{ $audit->asset->name }}
                                </p>
                                <span class="text-xs text-gray-500 bg-gray-100 px-2 py-1 rounded-full">
                                    {{ $audit->asset->department->name ?? 'No Department' }}
                                </span>
                            </div>
                            <div class="flex justify-between mt-1">
                                <p class="text-sm text-gray-500">
                                    {{ \Carbon\Carbon::parse($audit->audit_date)->format('M d, Y') }}
                                </p>
                                <p class="text-sm px-2 py-0.5 rounded-full
                                    {{ $audit->previous_condition !== $audit->new_condition ? 'text-orange-700 bg-orange-100' : 'text-green-700 bg-green-100' }}">
                                    {{ ucfirst($audit->new_condition ?? 'unknown') }}
                                </p>
                            </div>
                            @if($audit->discrepancies)
                                <p class="text-xs text-red-600 mt-1">
                                    <span class="font-semibold">Discrepancy:</span> {{ Str::limit($audit->discrepancies, 50) }}
                                </p>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="text-center text-gray-500 py-4">
                        <p class="text-sm">No recent audits found</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Upcoming Audits -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Upcoming Audits</h3>
            <div class="space-y-4 max-h-64 overflow-y-auto">
                @forelse($upcomingAudits as $asset)
                    <div class="flex items-center p-4 bg-blue-50 rounded-lg">
                        <div class="flex-1">
                            <div class="flex justify-between items-start">
                                <p class="text-sm font-medium text-blue-900">
                                    {{ $asset->name }}
                                </p>
                                <span class="text-xs text-blue-700 bg-blue-100 px-2 py-1 rounded-full">
                                    {{ $asset->department->name ?? 'No Department' }}
                                </span>
                            </div>
                            <div class="flex justify-between mt-1">
                                <p class="text-sm text-blue-700">
                                    {{ $asset->model }} ({{ $asset->manufacturer }})
                                </p>
                                <p class="text-xs px-2 py-0.5 rounded-full text-gray-700 bg-gray-100">
                                    {{ ucfirst($asset->condition ?? 'unknown') }}
                                </p>
                            </div>
                            <p class="text-xs text-blue-600 mt-1">
                                <span class="font-semibold">Location:</span> {{ $asset->current_location ?? 'Unknown' }}
                            </p>
                        </div>
                    </div>
                @empty
                    <div class="text-center text-gray-500 py-4">
                        <p class="text-sm">No upcoming audits found</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Monthly Progress and Asset Condition -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Monthly Audits Progress -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Monthly Progress</h3>
            <div class="flex items-center mt-2">
                <div class="w-full bg-gray-200 rounded-full h-5">
                    @php
                        $progress = $stats['pending_audits'] > 0
                            ? min(100, round(($stats['last_month_audits'] / max(1, $stats['pending_audits'])) * 100))
                            : 100;
                    @endphp
                    <div class="bg-emerald-600 h-5 rounded-full" style="width: {{ $progress }}%"></div>
                </div>
                <div class="ml-4 whitespace-nowrap">
                    <span class="text-lg font-semibold text-gray-900">{{ $progress }}%</span>
                </div>
            </div>
            <div class="flex justify-between mt-2 text-sm text-gray-600">
                <div>
                    <span class="font-medium">Last Month:</span> {{ number_format($stats['last_month_audits']) }} audits
                </div>
                <div>
                    <span class="font-medium">This Week:</span> {{ number_format($stats['this_week_audits']) }} audits
                </div>
            </div>
        </div>

        <!-- Asset Condition Chart -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Asset Conditions</h3>
            <div class="h-64">
                @php
                    $conditionColors = [
                        'new' => 'bg-green-500',
                        'good' => 'bg-blue-500',
                        'fair' => 'bg-yellow-500',
                        'poor' => 'bg-red-500',
                    ];
                    $totalAssets = array_sum($stats['assets_by_condition'] ?? []);
                @endphp
                @if($totalAssets > 0)
                    <div class="grid grid-cols-4 gap-4 h-full">
                        @foreach($stats['assets_by_condition'] as $condition => $count)
                            <div class="flex flex-col">
                                <div class="flex-1 bg-gray-100 rounded-lg relative">
                                    <div class="absolute bottom-0 w-full {{ $conditionColors[$condition] ?? 'bg-gray-500' }} rounded-lg transition-all duration-500"
                                         style="height: {{ $totalAssets > 0 ? ($count / $totalAssets * 100) : 0 }}%">
                                    </div>
                                </div>
                                <div class="text-center mt-2">
                                    <span class="text-sm font-medium text-gray-600">{{ ucfirst($condition) }}</span>
                                    <span class="block text-lg font-semibold text-gray-900">{{ number_format($count) }}</span>
                                    @if($totalAssets > 0)
                                        <span class="text-sm text-gray-500">{{ round(($count / $totalAssets) * 100, 1) }}%</span>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center text-gray-500 py-4">
                        <p class="text-sm">No asset condition data available</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Loading State -->
    <div wire:loading.flex class="fixed inset-0 -top-10 bg-black bg-opacity-25 items-center justify-center z-50">
        <div class="p-4 center flex-col">
            <i data-lucide="loader-circle" class="text-4xl h-10 w-10 text-emerald-600 animate-spin"></i>
            <p class="text-gray-500 mt-2">Loading...</p>
        </div>
    </div>
</div>

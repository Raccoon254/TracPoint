<div class="max-w-7xl mx-auto py-6 space-y-6">
    <!-- Header Section with Refresh Button -->
    <div class="flex justify-between items-center">
        <h2 class="text-xl font-semibold text-gray-900">
            My Asset Dashboard
        </h2>

        <button wire:click="refreshData" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-emerald-600 hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500">
            <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
            </svg>
            Refresh
        </button>
    </div>

    <!-- Main Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Assigned Assets Card -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100">
                    <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                    </svg>
                </div>
                <div class="ml-4">
                    <h4 class="text-lg font-semibold text-gray-900">Assigned Assets</h4>
                    <p class="text-3xl font-bold text-gray-900">{{ number_format($stats['total_assigned_assets']) }}</p>
                </div>
            </div>
        </div>

        <!-- Pending Returns Card -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-yellow-100">
                    <svg class="h-6 w-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div class="ml-4">
                    <h4 class="text-lg font-semibold text-gray-900">Pending Returns</h4>
                    <p class="text-3xl font-bold text-gray-900">{{ number_format($stats['pending_returns']) }}</p>
                </div>
            </div>
        </div>

        <!-- Pending Requests Card -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-purple-100">
                    <svg class="h-6 w-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                </div>
                <div class="ml-4">
                    <h4 class="text-lg font-semibold text-gray-900">Pending Requests</h4>
                    <p class="text-3xl font-bold text-gray-900">{{ number_format($stats['pending_requests']) }}</p>
                </div>
            </div>
        </div>

        <!-- Maintenance Due Card -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-red-100">
                    <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 13l-7 7-7-7m14-8l-7 7-7-7" />
                    </svg>
                </div>
                <div class="ml-4">
                    <h4 class="text-lg font-semibold text-gray-900">Maintenance Due</h4>
                    <p class="text-3xl font-bold text-gray-900">{{ number_format($stats['maintenance_due']) }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Assigned Assets and Maintenance Schedule Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Assigned Assets -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">My Assigned Assets</h3>
            <div class="space-y-4 max-h-96 overflow-y-auto">
                @forelse($assignedAssets as $asset)
                    <div class="flex items-center p-4 bg-gray-50 rounded-lg">
                        <div class="flex-1">
                            <div class="flex justify-between items-start">
                                <div>
                                    <p class="text-sm font-medium text-gray-900">
                                        {{ $asset->name }}
                                    </p>
                                    <p class="text-xs text-gray-500">
                                        Code: {{ $asset->asset_code }}
                                    </p>
                                </div>
                                <span class="text-xs text-gray-600 bg-gray-100 px-2 py-1 rounded-full">
                                    {{ $asset->category->name }}
                                </span>
                            </div>
                            <div class="mt-2 flex justify-between items-center">
                                <span class="text-xs text-gray-500">
                                    Assigned: {{ \Carbon\Carbon::parse($asset->assigned_date)->format('M d, Y') }}
                                </span>
                                @if($asset->expected_return_date)
                                    <span class="text-xs {{ \Carbon\Carbon::parse($asset->expected_return_date)->isPast() ? 'text-red-600' : 'text-yellow-600' }}">
                                        Return: {{ \Carbon\Carbon::parse($asset->expected_return_date)->format('M d, Y') }}
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div>
                        @include('partials.empty', [
                            'title' => 'You have no assigned assets',
                            'message' => 'Assets assigned to you will appear here.',
                            'icon' => 'asset'
                        ])
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Maintenance Schedule -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Maintenance Schedule</h3>
            <div class="space-y-4 max-h-96 overflow-y-auto">
                @forelse($maintenanceSchedule as $maintenance)
                    <div class="flex items-center p-4 {{ $maintenance->next_maintenance_date <= now() ? 'bg-red-50' : 'bg-yellow-50' }} rounded-lg">
                        <div class="flex-1">
                            <div class="flex justify-between items-start">
                                <div>
                                    <p class="text-sm font-medium {{ $maintenance->next_maintenance_date <= now() ? 'text-red-900' : 'text-yellow-900' }}">
                                        {{ $maintenance->asset->name }}
                                    </p>
                                    <p class="text-xs {{ $maintenance->next_maintenance_date <= now() ? 'text-red-700' : 'text-yellow-700' }}">
                                        Type: {{ str_replace('_', ' ', ucfirst($maintenance->maintenance_type)) }}
                                    </p>
                                </div>
                                <span class="text-xs {{ $maintenance->next_maintenance_date <= now() ? 'text-red-700 bg-red-100' : 'text-yellow-700 bg-yellow-100' }} px-2 py-1 rounded-full">
                                    {{ ucfirst($maintenance->status) }}
                                </span>
                            </div>
                            <p class="text-sm {{ $maintenance->next_maintenance_date <= now() ? 'text-red-700' : 'text-yellow-700' }} mt-2">
                                Due {{ \Carbon\Carbon::parse($maintenance->next_maintenance_date)->diffForHumans() }}
                            </p>
                        </div>
                    </div>
                @empty
                    <div>
                        @include('partials.empty', [
                            'title' => 'No maintenance schedule',
                            'message' => 'Your maintenance schedule will appear here.',
                            'icon' => 'tool'
                        ])
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Recent Activities and Pending Requests Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent Activities -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Recent Activities</h3>
            <div class="space-y-4 max-h-96 overflow-y-auto">
                @forelse($recentActivities as $activity)
                    <div class="flex items-center p-4 bg-gray-50 rounded-lg">
                        <div class="flex-1">
                            <div class="flex justify-between items-start">
                                <p class="text-sm font-medium text-gray-900">
                                    @switch($activity->activity_type)
                                        @case('asset')
                                            Asset {{ $activity->subject_name }} was {{ str_replace('_', ' ', $activity->action) }}
                                            @break
                                        @case('request')
                                            Request for {{ $activity->subject_name }} was {{ str_replace('_', ' ', $activity->action) }}
                                            @break
                                        @case('maintenance')
                                            {{ $activity->subject_name }} - {{ str_replace('_', ' ', $activity->action) }} maintenance
                                            @break
                                    @endswitch
                                </p>
                            </div>
                            <p class="text-sm text-gray-500 mt-1">
                                {{ \Carbon\Carbon::parse($activity->activity_date)->diffForHumans() }}
                            </p>
                        </div>
                    </div>
                @empty
                    <div>
                        @include('partials.empty', [
                            'title' => 'No recent activities',
                            'message' => 'Your recent activities will appear here.',
                            'icon' => 'activity'
                        ])
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Pending Requests -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Pending Requests</h3>
            <div class="space-y-4 max-h-96 overflow-y-auto">
                @forelse($pendingRequests as $request)
                    <div class="flex items-center p-4 bg-gray-50 rounded-lg">
                        <div class="flex-1">
                            <div class="flex justify-between items-start">
                                <div>
                                    <p class="text-sm font-medium text-gray-900">
                                        {{ $request->purpose }}
                                    </p>
                                    <p class="text-xs text-gray-500">
                                        Category: {{ $request->category->name }}
                                    </p>
                                </div>
                                <span class="text-xs {{ $request->status === 'pending' ? 'text-yellow-700 bg-yellow-100' : 'text-green-700 bg-green-100' }} px-2 py-1 rounded-full">
                                    {{ ucfirst($request->status) }}
                                </span>
                            </div>
                            <div class="mt-2 flex justify-between items-center text-xs text-gray-500">
                                <span>Requested: {{ \Carbon\Carbon::parse($request->created_at)->format('M d, Y') }}</span>
                                <span>Required: {{ \Carbon\Carbon::parse($request->required_from)->format('M d, Y') }}</span>
                            </div>
                        </div>
                    </div>
                @empty
                    <div>
                        @include('partials.empty', [
                            'title' => 'No pending requests',
                            'message' => 'Your pending requests will appear here.',
                            'icon' => 'user'
                        ])
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Loading State -->
    <div wire:loading.flex class="fixed inset-0 bg-black bg-opacity-25 items-center justify-center z-50">
        <div class="bg-white p-4 rounded-lg shadow-lg flex flex-col items-center">
            <svg class="animate-spin h-10 w-10 text-emerald-600 mb-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <p class="text-gray-700">Loading your dashboard...</p>
        </div>
    </div>

    <!-- Assets by Category Chart -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Assets by Category</h3>
        <div class="h-64">
            @if(count($this->assetsByCategory) > 0)
                <div class="grid grid-cols-{{ count($this->assetsByCategory) }} gap-4 h-full">
                    @foreach($this->assetsByCategory as $category => $count)
                        <div class="flex flex-col">
                            <div class="flex-1 bg-emerald-100 rounded-lg relative">
                                <div class="absolute bottom-0 w-full bg-emerald-500 rounded-lg transition-all duration-500"
                                     style="height: {{ ($count / array_sum($this->assetsByCategory) * 100) }}%">
                                </div>
                            </div>
                            <div class="text-center mt-2">
                                <span class="text-sm font-medium text-gray-600">{{ $category }}</span>
                                <span class="block text-lg font-semibold text-gray-900">{{ $count }}</span>
                                <span class="text-sm text-gray-500">{{ round(($count / array_sum($this->assetsByCategory) * 100), 1) }}%</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="flex items-center justify-center h-full">
                    @include('partials.empty', [
                        'title' => 'No assets by category',
                        'message' => 'Assets by category will appear here.',
                        'icon' => 'category'
                    ])
                </div>
            @endif
        </div>
    </div>

    <!-- Upcoming Returns Alert -->
    @if(count($this->upcomingReturns) > 0)
        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded-lg">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-yellow-800">Upcoming Asset Returns</h3>
                    <div class="mt-2 text-sm text-yellow-700">
                        <ul class="list-disc pl-5 space-y-1">
                            @foreach($this->upcomingReturns as $asset)
                                <li>
                                    {{ $asset['name'] }} - Due {{ \Carbon\Carbon::parse($asset['expected_return_date'])->format('M d, Y') }}
                                    ({{ \Carbon\Carbon::parse($asset['expected_return_date'])->diffForHumans() }})
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

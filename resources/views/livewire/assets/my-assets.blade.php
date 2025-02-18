<div class="max-w-7xl mx-auto py-12 space-y-6">
    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Total Current Assets -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <h4 class="text-lg font-semibold text-gray-900">Current Assets</h4>
                    <p class="text-3xl font-bold text-gray-900">{{ number_format($assetStats['total_current']) }}</p>
                </div>
            </div>
        </div>

        <!-- Total Value -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-emerald-100">
                    <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <h4 class="text-lg font-semibold text-gray-900">Total Value</h4>
                    <p class="text-3xl font-bold text-gray-900">${{ number_format($assetStats['total_value'], 2) }}</p>
                </div>
            </div>
        </div>

        <!-- Pending Returns -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-yellow-100">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <h4 class="text-lg font-semibold text-gray-900">Pending Returns</h4>
                    <p class="text-3xl font-bold text-gray-900">{{ number_format($assetStats['pending_returns']) }}</p>
                </div>
            </div>
        </div>

        <!-- Overdue Returns -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-red-100">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <h4 class="text-lg font-semibold text-gray-900">Overdue Returns</h4>
                    <p class="text-3xl font-bold text-gray-900">{{ number_format($assetStats['overdue_returns']) }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label for="search" class="block text-sm font-medium text-gray-700">Search</label>
                <input type="text" wire:model.live.debounce.300ms="search" id="search"
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm"
                       placeholder="Search assets...">
            </div>

            <div>
                <label for="categoryFilter" class="block text-sm font-medium text-gray-700">Category</label>
                <select wire:model.live="categoryFilter" id="categoryFilter"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm">
                    <option value="">All Categories</option>
                    @foreach($assetCategories as $id => $name)
                        <option value="{{ $id }}">{{ $name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="conditionFilter" class="block text-sm font-medium text-gray-700">Condition</label>
                <select wire:model.live="conditionFilter" id="conditionFilter"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm">
                    <option value="">All Conditions</option>
                    <option value="new">New</option>
                    <option value="good">Good</option>
                    <option value="fair">Fair</option>
                    <option value="poor">Poor</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Tabs -->
    <div class="border-b border-gray-200">
        <nav class="-mb-px flex space-x-8">
            <button wire:click="$set('activeTab', 'current')"
                    class="whitespace-nowrap pb-4 px-1 border-b-2 font-medium text-sm
                    {{ $activeTab === 'current'
                        ? 'border-emerald-500 text-emerald-600'
                        : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                Current Assets
            </button>
            <button wire:click="$set('activeTab', 'previous')"
                    class="whitespace-nowrap pb-4 px-1 border-b-2 font-medium text-sm
                    {{ $activeTab === 'previous'
                        ? 'border-emerald-500 text-emerald-600'
                        : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                Previous Assets
            </button>
        </nav>
    </div>

    <!-- Asset List -->
    <div class="bg-white shadow-sm rounded-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Asset</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Department</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Condition</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dates</th>
                    <th scope="col" class="relative px-6 py-3">
                        <span class="sr-only">Actions</span>
                    </th>
                </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                @foreach($activeTab === 'current' ? $currentAssets : $previousAssets as $asset)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                @if($asset->assetImages->isNotEmpty())
                                    <div class="flex-shrink-0 h-10 w-10">
                                        <img class="h-10 w-10 rounded-full object-cover"
                                             src="{{ Storage::url($asset->assetImages->first()->image_path) }}"
                                             alt="{{ $asset->name }}">
                                    </div>
                                @endif
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $asset->name }}</div>
                                    <div class="text-sm text-gray-500">{{ $asset->asset_code }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $asset->category->name }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $asset->department->name ?? 'N/A' }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                    {{ $asset->status === 'assigned' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                    {{ ucfirst($asset->status) }}
                                </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                    @switch($asset->condition)
                                        @case('new')
                                            bg-blue-100 text-blue-800
                                            @break
                                        @case('good')
                                            bg-green-100 text-green-800
                                            @break
                                        @case('fair')
                                    bg-yellow-100 text-yellow-800
                                    @break
                                @case('poor')
                                    bg-red-100 text-red-800
                                    @break
                            @endswitch">
                            {{ ucfirst($asset->condition) }}
                        </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">
                                @if($asset->assigned_date)
                                    <div>Assigned: {{ $asset->assigned_date->format('M d, Y') }}</div>
                                @endif
                                @if($asset->expected_return_date)
                                    <div class="{{ $asset->expected_return_date->isPast() ? 'text-red-600' : 'text-gray-500' }}">
                                        Return: {{ $asset->expected_return_date->format('M d, Y') }}
                                    </div>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            @if($activeTab === 'current')
                                <div class="flex justify-end space-x-2">
                                    <a href="{{ route('assets.show', $asset) }}"
                                       class="text-emerald-600 hover:text-emerald-900">View</a>
                                    @if($asset->status === 'assigned')
                                        <button wire:click="$dispatch('openReturnModal', { assetId: {{ $asset->id }} })"
                                                class="text-red-600 hover:text-red-900">
                                            Return
                                        </button>
                                    @endif
                                </div>
                            @else
                                <a href="{{ route('assets.show', $asset) }}"
                                   class="text-emerald-600 hover:text-emerald-900">View History</a>
                            @endif
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if(($activeTab === 'current' ? $currentAssets : $previousAssets)->hasPages())
            <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
                {{ ($activeTab === 'current' ? $currentAssets : $previousAssets)->links() }}
            </div>
        @endif
    </div>

    <!-- No Results Message -->
    @if(($activeTab === 'current' ? $currentAssets : $previousAssets)->isEmpty())
        <div class="text-center py-12">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900">No assets found</h3>
            <p class="mt-1 text-sm text-gray-500">
                {{ $activeTab === 'current'
                    ? 'You currently have no assets assigned to you.'
                    : 'You have no previous asset assignments.' }}
            </p>
            @if($activeTab === 'current')
                <div class="mt-6">
                    <a href="{{ route('requests.create') }}"
                       class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-emerald-600 hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500">
                        <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Request New Asset
                    </a>
                </div>
            @endif
        </div>
    @endif

    <!-- Maintenance Alerts -->
    @if($activeTab === 'current' && $currentAssets->isNotEmpty())
        <div class="mt-6">
            <h3 class="text-lg font-medium text-gray-900">Maintenance Schedule</h3>
            <div class="mt-4 space-y-4">
                @forelse($currentAssets->filter(function($asset) {
                    return $asset->maintenanceRecords
                        ->where('status', '!=', 'completed')
                        ->where('next_maintenance_date', '<=', now()->addDays(30))
                        ->isNotEmpty();
                }) as $asset)
                    @foreach($asset->maintenanceRecords->where('status', '!=', 'completed') as $maintenance)
                        <div class="bg-yellow-50 p-4 rounded-md">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-yellow-800">
                                        Maintenance Due: {{ $asset->name }}
                                    </h3>
                                    <div class="mt-2 text-sm text-yellow-700">
                                        <p>Type: {{ ucfirst($maintenance->maintenance_type) }}</p>
                                        <p>Due Date: {{ $maintenance->next_maintenance_date->format('M d, Y') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @empty
                    <p class="text-sm text-gray-500">No upcoming maintenance scheduled.</p>
                @endforelse
            </div>
        </div>
    @endif
</div>

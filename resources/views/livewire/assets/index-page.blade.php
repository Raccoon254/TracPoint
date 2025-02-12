<div class="min-h-screen py-12 bg-gray-50">
    <!-- Header -->
    <div class="bg-white shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <div class="flex items-center justify-between">
                <h1 class="text-2xl font-bold text-gray-900">Browse Assets</h1>
                <a href="{{ route('assets.create') }}"
                   class="inline-flex items-center px-4 py-2 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-emerald-600 hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500">
                    <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    Add Asset
                </a>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
            <!-- Filters Sidebar -->
            <div class="lg:col-span-1 space-y-6">
                <div class="bg-white rounded-xl shadow-sm p-6 space-y-6">
                    <!-- Search -->
                    <div>
                        <label for="search" class="block text-sm font-medium text-gray-700">Search</label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <input type="text" wire:model.live="search"
                                   class="block w-full rounded-lg border-gray-300 pr-10 focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm"
                                   placeholder="Search assets...">
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Price Range -->
                    <div x-data="{ open: true }">
                        <button @click="open = !open" class="flex items-center justify-between w-full text-sm font-medium text-gray-900">
                            <span>Price Range</span>
                            <svg class="h-5 w-5 text-gray-500" :class="{'transform rotate-180': open}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div x-show="open" class="mt-4">
                            <div class="flex justify-between mb-2">
                                <span class="text-sm text-gray-600">${{ number_format($priceRange[0]) }}</span>
                                <span class="text-sm text-gray-600">${{ $maxPriceRange }}</span>
                            </div>
                            <div class="relative">
                                <input type="range"
                                       wire:model.live="priceRange.0"
                                       min="0"
                                       max="{{ $maxPriceRange }}"
                                       step="{{ $maxPriceRange / 5 }}"
                                       class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer">
                                <input type="range"
                                       wire:model.lazy="priceRange.1"
                                       min="0"
                                       max="{{ $maxPriceRange }}"
                                       step="{{ $maxPriceRange / 5 }}"
                                       class="absolute top-0 w-full h-2 bg-transparent appearance-none cursor-pointer">
                            </div>
                        </div>
                    </div>

                    <!-- Categories -->
                    <div x-data="{ open: true }">
                        <button @click="open = !open" class="flex items-center justify-between w-full text-sm font-medium text-gray-900">
                            <span>Categories</span>
                            <svg class="h-5 w-5 text-gray-500" :class="{'transform rotate-180': open}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div x-show="open" class="mt-4 space-y-2">
                            @foreach($categories as $category)
                                <label class="flex items-center">
                                    <input type="checkbox" wire:model.live="selectedCategories" value="{{ $category->id }}"
                                           class="rounded border-gray-300 text-emerald-600 focus:ring-emerald-500">
                                    <span class="ml-2 text-sm text-gray-600">{{ $category->name }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <!-- Departments -->
                    <div x-data="{ open: true }">
                        <button @click="open = !open" class="flex items-center justify-between w-full text-sm font-medium text-gray-900">
                            <span>Departments</span>
                            <svg class="h-5 w-5 text-gray-500" :class="{'transform rotate-180': open}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div x-show="open" class="mt-4 space-y-2">
                            @foreach($departments as $department)
                                <label class="flex items-center">
                                    <input type="checkbox" wire:model.live="selectedDepartments" value="{{ $department->id }}"
                                           class="rounded border-gray-300 text-emerald-600 focus:ring-emerald-500">
                                    <span class="ml-2 text-sm text-gray-600">{{ $department->name }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <!-- Status -->
                    <div x-data="{ open: true }">
                        <button @click="open = !open" class="flex items-center justify-between w-full text-sm font-medium text-gray-900">
                            <span>Status</span>
                            <svg class="h-5 w-5 text-gray-500" :class="{'transform rotate-180': open}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div x-show="open" class="mt-4 space-y-2">
                            @foreach($statusOptions as $status)
                                <label class="flex items-center">
                                    <input type="checkbox" wire:model.live="selectedStatuses" value="{{ $status }}"
                                           class="rounded border-gray-300 text-emerald-600 focus:ring-emerald-500">
                                    <span class="ml-2 text-sm text-gray-600">{{ ucfirst($status) }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <!-- Condition -->
                    <div x-data="{ open: true }">
                        <button @click="open = !open" class="flex items-center justify-between w-full text-sm font-medium text-gray-900">
                            <span>Condition</span>
                            <svg class="h-5 w-5 text-gray-500" :class="{'transform rotate-180': open}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div x-show="open" class="mt-4 space-y-2">
                            @foreach($conditionOptions as $condition)
                                <label class="flex items-center">
                                    <input type="checkbox" wire:model.live="selectedConditions" value="{{ $condition }}"
                                           class="rounded border-gray-300 text-emerald-600 focus:ring-emerald-500">
                                    <span class="ml-2 text-sm text-gray-600">{{ ucfirst($condition) }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <div class="pt-4">
                        <button wire:click="clearFilters"
                                class="w-full inline-flex justify-center items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500">
                            Clear Filters
                        </button>
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <div class="lg:col-span-3 space-y-6">
                <!-- Toolbar -->
                <div class="bg-white rounded-xl shadow-sm p-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-4">
                            <select wire:model.live="sortBy"
                                    class="rounded-lg border-gray-300 text-sm focus:ring-emerald-500 focus:border-emerald-500">
                                @foreach($sortOptions as $value => $label)
                                    <option value="{{ $value }}">{{ $label }}</option>
                                @endforeach
                            </select>

                            <button wire:click="toggleView" class="p-2 hover:bg-gray-100 rounded-lg">
                                @if($view === 'grid')
                                    <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                                    </svg>
                                @else
                                    <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                                    </svg>
                                @endif
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Assets Grid/List -->
                <div wire:loading.class="opacity-50 pointer-events-none">
                    @if($view === 'grid')
                        @if($assets->isEmpty())
                            <div>
                                @include('partials.empty', [
                                   'title' => 'No Assets Found',
                                   'message' => 'Try adjusting your search filters or add a new asset.',
                                   'icon' => 'asset'
                               ])

                                <div class="center">
                                    @if($search || $selectedCategories || $selectedDepartments || $selectedStatuses || $selectedConditions)
                                        <button wire:click="clearFilters"
                                                class="mt-2 inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500">
                                            Clear Filters
                                        </button>
                                    @endif
                                </div>
                            </div>
                        @else
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                @foreach($assets as $asset)
                                    <div class="group bg-white rounded-xl shadow-sm hover:shadow-md transition-all duration-200 overflow-hidden"
                                         x-data="{ showActions: false }"
                                         @mouseenter="showActions = true"
                                         @mouseleave="showActions = false">
                                        <!-- Asset Image -->
                                        <div class="relative aspect-w-4 aspect-h-3">
                                            <div class="bg-gray-100 h-40 md:h-48 lg:h-56">
                                                @if($asset->assetImages->where('image_type', 'primary')->first())
                                                    <img src="{{ Storage::url($asset->assetImages->where('image_type', 'primary')->first()->image_path) }}"
                                                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                                                         alt="{{ $asset->name }}">
                                                @else
                                                    <div class="w-full h-full bg-gray-100 flex items-center justify-center">
                                                        <svg class="w-12 h-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                        </svg>
                                                    </div>
                                                @endif
                                            </div>
                                            <!-- Status Badge -->
                                            <div class="absolute top-2 right-2">
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full
                                            @if($asset->status === 'available') bg-green-100 text-green-800
                                            @elseif($asset->status === 'assigned') bg-blue-100 text-blue-800
                                            @elseif($asset->status === 'in_maintenance') bg-yellow-100 text-yellow-800
                                            @else bg-gray-100 text-gray-800
                                            @endif">
                                            {{ ucfirst($asset->status) }}
                                        </span>
                                            </div>
                                        </div>

                                        <!-- Asset Info -->
                                        <div class="p-4">
                                            <div class="flex items-start justify-between">
                                                <div>
                                                    <h3 class="text-sm font-medium text-gray-900">
                                                        <a href="{{ route('assets.show', $asset) }}" class="hover:text-emerald-600">
                                                            {{ $asset->name }}
                                                        </a>
                                                    </h3>
                                                    <p class="text-sm text-gray-500">{{ $asset->asset_code }}</p>
                                                </div>
                                                <p class="text-sm font-medium text-gray-900">${{ number_format($asset->value, 2) }}</p>
                                            </div>

                                            <div class="mt-4 flex items-center justify-between">
                                                <div class="flex items-center space-x-2">
                                            <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-gray-100 text-gray-800">
                                                {{ $asset->category->name }}
                                            </span>
                                                    <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium
                                                @if($asset->condition === 'new') bg-green-100 text-green-800
                                                @elseif($asset->condition === 'good') bg-blue-100 text-blue-800
                                                @elseif($asset->condition === 'fair') bg-yellow-100 text-yellow-800
                                                @else bg-red-100 text-red-800
                                                @endif">
                                                {{ ucfirst($asset->condition) }}
                                            </span>
                                                </div>
                                            </div>

                                            <!-- Quick Actions -->
                                            <div x-show="showActions"
                                                 x-transition:enter="transition ease-out duration-200"
                                                 x-transition:enter-start="opacity-0 translate-y-1"
                                                 x-transition:enter-end="opacity-100 translate-y-0"
                                                 x-transition:leave="transition ease-in duration-150"
                                                 x-transition:leave-start="opacity-100 translate-y-0"
                                                 x-transition:leave-end="opacity-0 translate-y-1"
                                                 class="mt-4 flex items-center justify-end space-x-2">
                                                <a href="{{ route('assets.show', $asset) }}"
                                                   class="inline-flex items-center px-2 py-1 text-xs font-medium text-gray-700 hover:text-emerald-600">
                                                    View Details
                                                </a>
                                                <a href="{{ route('assets.edit', $asset) }}"
                                                   class="inline-flex items-center px-2 py-1 text-xs font-medium text-gray-700 hover:text-emerald-600">
                                                    Edit
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    @else
                        @if($assets->isEmpty())
                            <div>
                                @include('partials.empty', [
                                   'title' => 'No Assets Found',
                                   'message' => 'Try adjusting your search filters or add a new asset.',
                                   'icon' => 'asset'
                               ])

                                <div class="center">
                                    @if($search || $selectedCategories || $selectedDepartments || $selectedStatuses || $selectedConditions)
                                        <button wire:click="clearFilters"
                                                class="mt-2 inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500">
                                            Clear Filters
                                        </button>
                                    @endif
                                </div>
                            </div>
                        @else
                        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Asset</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Department</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Value</th>
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($assets as $asset)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-10 w-10">
                                                    @if($asset->assetImages->where('image_type', 'primary')->first())
                                                        <img src="{{ Storage::url($asset->assetImages->where('image_type', 'primary')->first()->image_path) }}"
                                                             class="h-10 w-10 rounded-lg object-cover"
                                                             alt="{{ $asset->name }}">
                                                    @else
                                                        <div class="h-10 w-10 rounded-lg bg-gray-100 flex items-center justify-center">
                                                            <svg class="w-6 h-6 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                            </svg>
                                                        </div>
                                                    @endif
                                                </div>
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
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                                @if($asset->status === 'available') bg-green-100 text-green-800
                                                @elseif($asset->status === 'assigned') bg-blue-100 text-blue-800
                                                @elseif($asset->status === 'in_maintenance') bg-yellow-100 text-yellow-800
                                                @else bg-gray-100 text-gray-800
                                                @endif">
                                                {{ ucfirst($asset->status) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ $asset->department->name ?? 'N/A' }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">${{ number_format($asset->value, 2) }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <a href="{{ route('assets.show', $asset) }}" class="text-emerald-600 hover:text-emerald-900 mr-3">View</a>
                                            <a href="{{ route('assets.edit', $asset) }}" class="text-emerald-600 hover:text-emerald-900">Edit</a>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        @endif
                    @endif

                    <!-- Pagination -->
                    @if($assets->hasPages())
                        <div class="mt-6">
                            {{ $assets->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

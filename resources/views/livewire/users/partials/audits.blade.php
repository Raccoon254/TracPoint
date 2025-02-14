<div class="space-y-6">
    <!-- Filters -->
    <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-100">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-medium text-gray-900 flex items-center">
                <i data-lucide="filter" class="w-5 h-5 mr-2 text-gray-500"></i>
                Audit Filters
            </h3>
            <span class="text-sm text-gray-500">{{ $audits->total() }} audits found</span>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label for="auditDateRange" class="block text-sm font-medium text-gray-700">Date Range</label>
                <div class="mt-1 relative rounded-lg">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i data-lucide="calendar" class="h-4 w-4 text-gray-400"></i>
                    </div>
                    <select wire:model.live="auditDateRange" id="auditDateRange"
                            class="pl-10 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm">
                        <option value="">All Time</option>
                        <option value="7">Last 7 Days</option>
                        <option value="30">Last 30 Days</option>
                        <option value="90">Last 90 Days</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <!-- Audits List -->
    <div class="bg-white shadow-sm rounded-lg overflow-hidden border border-gray-100">
        <div class="px-4 py-5 border-b border-gray-200 sm:px-6">
            <h3 class="text-lg font-medium text-gray-900 flex items-center">
                <i data-lucide="clipboard-check" class="w-5 h-5 mr-2 text-gray-500"></i>
                Audit History
            </h3>
        </div>

        <div class="flow-root">
            <ul role="list" class="divide-y divide-gray-200">
                @forelse($audits as $audit)
                    <li class="p-6 hover:bg-gray-50 transition duration-150">
                        <div class="flex items-start space-x-4">
                            <!-- Asset Icon -->
                            <div class="flex-shrink-0">
                                <div class="w-12 h-12 rounded-lg bg-blue-100 flex items-center justify-center">
                                    <i data-lucide="box" class="w-6 h-6 text-blue-600"></i>
                                </div>
                            </div>

                            <div class="flex-1 min-w-0">
                                <!-- Header Section -->
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-sm font-semibold text-gray-900">
                                            {{ $audit->asset->name }}
                                        </p>
                                        <p class="text-sm text-gray-500">
                                            Asset Code: {{ $audit->asset->asset_code }}
                                        </p>
                                    </div>
                                    <div class="flex items-center space-x-3">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            <i data-lucide="tag" class="w-3 h-3 mr-1"></i>
                                            {{ $audit->asset->category->name }}
                                        </span>
                                        <span class="inline-flex items-center text-sm text-gray-500">
                                            <i data-lucide="clock" class="w-4 h-4 mr-1"></i>
                                            {{ $audit->audit_date->format('M d, Y') }}
                                        </span>
                                    </div>
                                </div>

                                <!-- Condition Change -->
                                <div class="mt-4 bg-gray-50 rounded-lg p-4">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center space-x-4">
                                            <div class="text-sm">
                                                <span class="text-gray-500">Previous:</span>
                                                <span class="font-medium text-gray-900">{{ ucfirst($audit->previous_condition) }}</span>
                                            </div>
                                            <i data-lucide="arrow-right" class="w-4 h-4 text-gray-400"></i>
                                            <div class="text-sm">
                                                <span class="text-gray-500">New:</span>
                                                <span class="font-medium text-gray-900">{{ ucfirst($audit->new_condition) }}</span>
                                            </div>
                                        </div>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                            {{ $audit->location_verified ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                            <i data-lucide="{{ $audit->location_verified ? 'check-circle' : 'alert-circle' }}" class="w-3 h-3 mr-1"></i>
                                            {{ $audit->location_verified ? 'Location Verified' : 'Location Unverified' }}
                                        </span>
                                    </div>
                                </div>

                                <!-- Discrepancies & Actions -->
                                @if($audit->discrepancies || $audit->action_taken)
                                    <div class="mt-4 space-y-3">
                                        @if($audit->discrepancies)
                                            <div class="flex items-start space-x-2">
                                                <i data-lucide="alert-triangle" class="w-4 h-4 text-red-500 mt-0.5"></i>
                                                <p class="text-sm text-red-600">{{ $audit->discrepancies }}</p>
                                            </div>
                                        @endif
                                        @if($audit->action_taken)
                                            <div class="flex items-start space-x-2">
                                                <i data-lucide="check-square" class="w-4 h-4 text-green-500 mt-0.5"></i>
                                                <p class="text-sm text-gray-600">{{ $audit->action_taken }}</p>
                                            </div>
                                        @endif
                                    </div>
                                @endif

                                <!-- Audit Images -->
                                @if($audit->images)
                                    <div class="mt-4 grid grid-cols-4 gap-4">
                                        @foreach($audit->images as $image)
                                            <div class="relative group">
                                                <img src="{{ Storage::url($image) }}"
                                                     alt="Audit Image"
                                                     class="h-24 w-full object-cover rounded-lg shadow-sm">
                                                <div class="absolute inset-0 bg-black bg-opacity-40 opacity-0 group-hover:opacity-100 transition-opacity duration-200 rounded-lg flex items-center justify-center">
                                                    <i data-lucide="zoom-in" class="w-6 h-6 text-white"></i>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </div>
                    </li>
                @empty
                    <li class="p-6">
                        @include('partials.empty', [
                            'title' => 'No Audits',
                            'message' => 'Audits will appear here once conducted.',
                            'icon' => 'user'
                        ])
                    </li>
                @endforelse
            </ul>
        </div>

        @if($audits->hasPages())
            <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                {{ $audits->links() }}
            </div>
        @endif
    </div>
</div>

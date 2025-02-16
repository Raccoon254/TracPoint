<div class="bg-white rounded-lg shadow-sm overflow-hidden border border-gray-100">
    <div class="px-4 py-5 border-b border-gray-200 sm:px-6">
        <h3 class="text-lg font-medium text-gray-900 flex items-center">
            <i data-lucide="box" class="w-5 h-5 mr-2 text-gray-500"></i>
            Assigned Assets
        </h3>
    </div>

    <div class="flow-root">
        <ul role="list" class="divide-y divide-gray-200">
            @forelse($assignedAssets as $asset)
                <li class="p-6 hover:bg-gray-50 transition duration-150">
                    <div class="flex items-center space-x-4">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 rounded-lg bg-blue-100 flex items-center justify-center">
                                <i data-lucide="box" class="w-6 h-6 text-blue-600"></i>
                            </div>
                        </div>

                        <div class="flex-1 min-w-0">
                            <!-- Header Section -->
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-semibold text-gray-900">{{ $asset->name }}</p>
                                    <p class="text-sm text-gray-500">{{ $asset->asset_code }}</p>
                                </div>
                                <button wire:click="initiateReturn({{ $asset->id }})"
                                        class="inline-flex items-center px-3 py-1 border border-transparent rounded-lg text-sm font-medium text-white bg-emerald-600 hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500">
                                    <i data-lucide="log-out" class="w-4 h-4 mr-1.5"></i>
                                    Return Asset
                                </button>
                            </div>

                            <!-- Details Section -->
                            <div class="mt-4 grid grid-cols-1 md:grid-cols-3 gap-4 bg-gray-50 rounded-lg p-4">
                                <div>
                                    <p class="text-xs text-gray-500">Category</p>
                                    <p class="text-sm font-medium text-gray-900">{{ $asset->category->name }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500">Department</p>
                                    <p class="text-sm font-medium text-gray-900">{{ $asset->department->name }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500">Condition</p>
                                    <p class="text-sm font-medium text-gray-900">{{ ucfirst($asset->condition) }}</p>
                                </div>
                            </div>

                            <!-- Timeline Section -->
                            <div class="mt-4 flex items-center space-x-6 text-sm text-gray-500">
                                <div class="flex items-center">
                                    <i data-lucide="calendar" class="w-4 h-4 mr-1.5 text-gray-400"></i>
                                    <span>Assigned: {{ $asset->assigned_date?->format('M d, Y') }}</span>
                                </div>
                                @if($asset->expected_return_date)
                                    <div class="flex items-center">
                                        <i data-lucide="calendar-clock" class="w-4 h-4 mr-1.5 text-gray-400"></i>
                                        <span>Expected Return: {{ $asset->expected_return_date->format('M d, Y') }}</span>
                                    </div>
                                @endif
                            </div>

                            @if($asset->maintenanceRecords()->where('status', '!=', 'completed')->exists())
                                <div class="mt-4 p-3 bg-yellow-50 rounded-lg border border-yellow-200">
                                    <div class="flex items-center space-x-2 text-sm text-yellow-800">
                                        <i data-lucide="alert-triangle" class="w-4 h-4"></i>
                                        <span>Maintenance required</span>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </li>
            @empty
                <li class="p-6">
                    <div class="text-center">
                        <div class="mx-auto w-12 h-12 rounded-full bg-gray-100 flex items-center justify-center">
                            <i data-lucide="package" class="w-6 h-6 text-gray-400"></i>
                        </div>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">No Assets Assigned</h3>
                        <p class="mt-1 text-sm text-gray-500">You currently have no assets assigned to you.</p>
                    </div>
                </li>
            @endforelse
        </ul>
    </div>

    @if($assignedAssets->hasPages())
        <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
            {{ $assignedAssets->links() }}
        </div>
    @endif
</div>

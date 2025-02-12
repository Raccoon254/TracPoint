<div class="space-y-6">
    <!-- Filters -->
    <div class="bg-white rounded-lg shadow-sm p-4">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label for="auditDateRange" class="block text-sm font-medium text-gray-700">Date Range</label>
                <select wire:model.live="auditDateRange" id="auditDateRange"
                        class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm">
                    <option value="">All Time</option>
                    <option value="7">Last 7 Days</option>
                    <option value="30">Last 30 Days</option>
                    <option value="90">Last 90 Days</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Audits List -->
    <div class="bg-white shadow-sm rounded-lg overflow-hidden">
        <div class="flow-root">
            <ul role="list" class="divide-y divide-gray-200">
                @forelse($audits as $audit)
                    <li class="p-4 hover:bg-gray-50">
                        <div class="flex items-center space-x-4">
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center justify-between">
                                    <p class="text-sm font-medium text-gray-900 truncate">
                                        {{ $audit->asset->name }}
                                    </p>
                                    <div class="flex items-center space-x-2">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            {{ $audit->asset->category->name }}
                                        </span>
                                        <span class="text-sm text-gray-500">
                                            {{ $audit->audit_date->format('M d, Y') }}
                                        </span>
                                    </div>
                                </div>
                                <div class="mt-2">
                                    <div class="flex items-center space-x-4 text-sm text-gray-500">
                                        <div>
                                            <span class="font-medium">Previous Condition:</span>
                                            <span class="ml-1">{{ ucfirst($audit->previous_condition) }}</span>
                                        </div>
                                        <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                        </svg>
                                        <div>
                                            <span class="font-medium">New Condition:</span>
                                            <span class="ml-1">{{ ucfirst($audit->new_condition) }}</span>
                                        </div>
                                    </div>
                                    @if($audit->discrepancies)
                                        <div class="mt-2">
                                            <p class="text-sm text-red-600">
                                                Discrepancies: {{ $audit->discrepancies }}
                                            </p>
                                        </div>
                                    @endif
                                    @if($audit->action_taken)
                                        <div class="mt-1">
                                            <p class="text-sm text-gray-600">
                                                Action Taken: {{ $audit->action_taken }}
                                            </p>
                                        </div>
                                    @endif
                                </div>
                                @if($audit->images)
                                    <div class="mt-2 flex space-x-2">
                                        @foreach($audit->images as $image)
                                            <img src="{{ Storage::url($image) }}"
                                                 alt="Audit Image"
                                                 class="h-16 w-16 object-cover rounded-lg">
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </div>
                    </li>
                @empty
                    <li class="p-4">
                        @include('partials.empty', [
                            'title' => 'No Audits Found',
                            'message' => 'No audits found for the selected filters.',
                            'icon' => 'user'
                        ])
                    </li>
                @endforelse
            </ul>
        </div>
        <div class="px-4 py-3 border-t border-gray-200">
            {{ $audits->links() }}
        </div>
    </div>
</div>

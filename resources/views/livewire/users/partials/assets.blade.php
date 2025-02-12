<div class="space-y-6">
    <!-- Filters -->
    <div class="bg-white rounded-lg shadow-sm p-4">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label for="assetSearch" class="block text-sm font-medium text-gray-700">Search Assets</label>
                <input type="text" wire:model.live="assetSearch" id="assetSearch"
                       class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm"
                       placeholder="Search by name or code...">
            </div>
            <div>
                <label for="assetStatus" class="block text-sm font-medium text-gray-700">Status</label>
                <select wire:model.live="assetStatus" id="assetStatus"
                        class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm">
                    <option value="">All Statuses</option>
                    <option value="assigned">Assigned</option>
                    <option value="in_maintenance">In Maintenance</option>
                    <option value="retired">Retired</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Assets List -->
    <div class="bg-white shadow-sm rounded-lg overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
            <tr>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Asset</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Assigned Date</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
            </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
            @forelse($assignedAssets as $asset)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div>
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
                            @if($asset->status === 'assigned') bg-green-100 text-green-800
                            @elseif($asset->status === 'in_maintenance') bg-yellow-100 text-yellow-800
                            @else bg-gray-100 text-gray-800
                            @endif">
                            {{ ucfirst(str_replace('_', ' ', $asset->status)) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $asset->assigned_date?->format('M d, Y') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <a href="{{ route('assets.show', $asset) }}" class="text-emerald-600 hover:text-emerald-900">View</a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                        @include('partials.empty', [
                            'title' => 'No Assets Found',
                            'message' => 'No assets found for the selected filters.',
                            'icon' => 'asset'
                        ])
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>

        <div class="px-4 py-3 border-t border-gray-200">
            {{ $assignedAssets->links() }}
        </div>
    </div>
</div>

<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-900">Asset Requests</h2>
        </div>

        <!-- Filters -->
        <div class="bg-white shadow-sm rounded-lg p-4 mb-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
                <!-- Search -->
                <div>
                    <label for="search" class="block text-sm font-medium text-gray-700">Search</label>
                    <input type="text" wire:model.live="search"
                           class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm"
                           placeholder="Search requests...">
                </div>

                <!-- Category Filter -->
                <div>
                    <label for="category" class="block text-sm font-medium text-gray-700">Category</label>
                    <select wire:model.live="selectedCategory"
                            class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm">
                        <option value="">All Categories</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Priority Filter -->
                <div>
                    <label for="priority" class="block text-sm font-medium text-gray-700">Priority</label>
                    <select wire:model.live="selectedPriority"
                            class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm">
                        <option value="">All Priorities</option>
                        @foreach($priorityOptions as $value => $label)
                            <option value="{{ $value }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Status Filter -->
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                    <select wire:model.live="selectedStatus"
                            class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm">
                        <option value="">All Statuses</option>
                        @foreach($statusOptions as $value => $label)
                            <option value="{{ $value }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <!-- Requests Table -->
        <div class="bg-white shadow-sm rounded-lg overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer"
                        wire:click="sortBy('created_at')">
                        <div class="flex items-center space-x-1">
                            <span>Request Date</span>
                            @if($sortField === 'created_at')
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    @if($sortDirection === 'asc')
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/>
                                    @else
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                    @endif
                                </svg>
                            @endif
                        </div>
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Requester</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Priority</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                @forelse($requests as $request)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $request->created_at->format('M d, Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $request->requester->name }}</div>
                            <div class="text-sm text-gray-500">{{ $request->requester->email }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $request->category->name }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                @if($request->priority === 'high') bg-red-100 text-red-800
                                @elseif($request->priority === 'medium') bg-yellow-100 text-yellow-800
                                @else bg-green-100 text-green-800
                                @endif">
                                {{ ucfirst($request->priority) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                @if($request->status === 'pending') bg-yellow-100 text-yellow-800
                                @elseif($request->status === 'approved') bg-blue-100 text-blue-800
                                @elseif($request->status === 'fulfilled') bg-green-100 text-green-800
                                @else bg-red-100 text-red-800
                                @endif">
                                {{ ucfirst($request->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <button wire:click="showRequest({{ $request->id }})"
                                    class="text-emerald-600 hover:text-emerald-900">
                                View/Action
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                            @include('partials.empty', [
                                'title' => 'No requests found',
                                'icon' => 'inbox',
                                'message' => 'No asset requests found matching your criteria.'
                            ])
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>

            <div class="px-4 py-3 border-t border-gray-200 {{ $requests->isEmpty() || $requests->total() <= $requests->perPage() ? 'hidden' : '' }}">
                {{ $requests->links() }}
            </div>
        </div>
    </div>

    <!-- Action Modal -->
    <div x-data="{ show: @entangle('showActionModal') }"
         x-show="show"
         x-on:open-modal.window="show = true"
         x-on:close-modal.window="show = false"
         x-bind:class="{ 'show': show }"
         x-cloak
         class="fixed inset-0 z-50 overflow-y-auto"
         wire:model="showActionModal">
        <!-- Background overlay -->
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>

        <div class="fixed inset-0 z-10 overflow-y-auto">
            <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                @if($currentRequest)
                    <div class="relative transform overflow-hidden rounded-lg bg-white px-4 pb-4 pt-5 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg sm:p-6">
                        <div class="absolute right-0 top-0 hidden pr-4 pt-4 sm:block">
                            <button wire:click="resetModal" type="button"
                                    class="rounded-md bg-white text-gray-400 hover:text-gray-500">
                                <span class="sr-only">Close</span>
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>

                        <!-- Request Details -->
                        <div class="sm:flex sm:items-start">
                            <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
                                <h3 class="text-lg font-semibold leading-6 text-gray-900">
                                    Request Details
                                </h3>

                                <div class="mt-4 space-y-4">
                                    <!-- Request Information -->
                                    <div class="bg-gray-50 rounded-lg p-4">
                                        <dl class="grid grid-cols-1 gap-x-4 gap-y-4 sm:grid-cols-2">
                                            <div>
                                                <dt class="text-sm font-medium text-gray-500">Requester</dt>
                                                <dd class="mt-1 text-sm text-gray-900">{{ $currentRequest->requester->name }}</dd>
                                            </div>
                                            <div>
                                                <dt class="text-sm font-medium text-gray-500">Category</dt>
                                                <dd class="mt-1 text-sm text-gray-900">{{ $currentRequest->category->name }}</dd>
                                            </div>
                                            <div>
                                                <dt class="text-sm font-medium text-gray-500">Required From</dt>
                                                <dd class="mt-1 text-sm text-gray-900">{{ $currentRequest->required_from->format('M d, Y') }}</dd>
                                            </div>
                                            <div>
                                                <dt class="text-sm font-medium text-gray-500">Required Until</dt>
                                                <dd class="mt-1 text-sm text-gray-900">{{ $currentRequest->required_until->format('M d, Y') }}</dd>
                                            </div>
                                            <div class="sm:col-span-2">
                                                <dt class="text-sm font-medium text-gray-500">Purpose</dt>
                                                <dd class="mt-1 text-sm text-gray-900">{{ $currentRequest->purpose }}</dd>
                                            </div>
                                        </dl>
                                    </div>

                                    <!-- Action Section -->
                                    @if($currentRequest->status === 'pending')
                                        <div class="space-y-4">
                                            <div class="flex justify-end space-x-3">
                                                <!-- Instead of calling rejectRequest immediately, toggle the rejection note -->
                                                <button type="button" wire:click="toggleRejectionNote"
                                                        class="inline-flex items-center px-4 py-2 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                                    Reject
                                                </button>
                                                <button type="button" wire:click="approveRequest"
                                                        class="inline-flex items-center px-4 py-2 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-emerald-600 hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500">
                                                    Approve
                                                </button>
                                            </div>

                                            <!-- Rejection Note Field -->
                                            <div x-data="{ show: @entangle('showRejectionNote') }" x-show="show" class="mt-4">
                                                <label for="rejectionNote" class="block text-sm font-medium text-gray-700">Rejection Note</label>
                                                <textarea wire:model="rejectionNote" rows="3"
                                                          class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm"
                                                          placeholder="Please provide a reason for rejection..."></textarea>
                                                @error('rejectionNote') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror

                                                <!-- Confirm Rejection Button -->
                                                <div class="mt-2 flex justify-end">
                                                    <button type="button" wire:click="rejectRequest"
                                                            class="inline-flex items-center px-4 py-2 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                                        Confirm Rejection
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    @endif

                                    <!-- Asset Assignment Section -->
                                    @if($currentRequest->status === 'approved' && !$currentRequest->asset_id)
                                        <div class="space-y-4">
                                            <div>
                                                <label for="selectedAsset" class="block text-sm font-medium text-gray-700">Select Asset</label>
                                                <select wire:model="selectedAsset"
                                                        class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm">
                                                    <option value="">Select an asset</option>
                                                    @foreach($availableAssets ?? [] as $asset)
                                                        <option value="{{ $asset->id }}">{{ $asset->name }} ({{ $asset->asset_code }})</option>
                                                    @endforeach
                                                </select>
                                                @error('selectedAsset') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                            </div>

                                            <div class="flex justify-end">
                                                <button type="button" wire:click="fulfillRequest"
                                                        class="inline-flex items-center px-4 py-2 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-emerald-600 hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500">
                                                    Assign Asset
                                                </button>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Notification -->
    <div x-data="{ show: false, message: '' }"
         @request-updated.window="show = true; message = $event.detail"
         x-show="show"
         x-init="setTimeout(() => show = false, 3000)"
         class="fixed bottom-4 right-4">
        <div class="bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg">
            <span x-text="message"></span>
        </div>
    </div>
</div>

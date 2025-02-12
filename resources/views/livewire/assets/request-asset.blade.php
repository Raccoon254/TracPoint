<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="md:flex md:items-center md:justify-between mb-6">
            <div class="min-w-0 flex-1">
                <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:truncate sm:text-3xl sm:tracking-tight">
                    Request Asset: {{ $asset->name }}
                </h2>
                <p class="mt-1 text-sm text-gray-500">
                    Category: {{ $asset->category->name }} | Status: {{ ucfirst($asset->status) }}
                </p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Request Form -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Pending Requests Alert -->

                <div class="space-y-6">
                    @if($pendingRequests->isNotEmpty())
                        <!-- Pending Asset Requests -->
                        <div class="bg-white shadow-sm border-l-4 border-red-500 rounded-none overflow-hidden">
                            <div class="px-6 py-4 border-b bg-gray-50">
                                <h3 class="text-lg font-semibold text-gray-900">Pending Asset Requests</h3>
                                <p class="text-sm text-gray-600">These are your current asset requests awaiting
                                    approval.</p>
                            </div>
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                        Quantity
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                        Purpose
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Required
                                        Dates
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                        Priority
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                        Actions
                                    </th>
                                </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($pendingRequests as $request)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            {{ $request->quantity }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $request->purpose }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $request->required_from->format('M d, Y') }}
                                            - {{ $request->required_until->format('M d, Y') }}
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
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            <button wire:click="cancelRequest({{ $request->id }})"
                                                    class="text-red-600 hover:text-red-900 flex items-center">
                                                Cancel
                                                <svg xmlns="http://www.w3.org/2000/svg" class="ml-1 h-4 w-4"
                                                     viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                     stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <circle cx="12" cy="12" r="10"/>
                                                    <path d="m15 9-6 6"/>
                                                    <path d="m9 9 6 6"/>
                                                </svg>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>

                <!-- Asset Details Summary -->
                <div class="bg-gray-50 rounded-lg p-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm font-medium text-gray-900">Asset Code</p>
                            <p class="mt-1 text-sm text-gray-500">{{ $asset->asset_code }}</p>
                        </div>
                        <div>
                            <div class="flex relative">
                                @foreach($asset->assetImages as $image)
                                    <div class="absolute"
                                         style="left: {{ $loop->index * 2 }}rem; z-index: {{ 100 - $loop->index }}">
                                        <img src="{{ Storage::url($image->image_path) }}"
                                             alt="Asset image"
                                             class="rounded-full ring-gray-50 ring-2 object-cover w-14 h-14">
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        @if($asset->department)
                            <div>
                                <p class="text-sm font-medium text-gray-900">Department</p>
                                <p class="mt-1 text-sm text-gray-500">{{ $asset->department->name }}</p>
                            </div>
                        @endif
                        @if($asset->value)
                            <div>
                                <p class="text-sm font-medium text-gray-900">Value</p>
                                <p class="mt-1 text-sm text-gray-500">${{ number_format($asset->value, 2) }}</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Request Form -->
                <div class="bg-white shadow-sm rounded-lg p-6">
                    <form wire:submit="submitRequest" class="space-y-6">
                        <!-- Purpose -->
                        <div>
                            <label for="purpose" class="block text-sm font-medium text-gray-700">Purpose</label>
                            <textarea wire:model="purpose" id="purpose" rows="3"
                                      class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm"
                                      placeholder="Explain why you need this asset..."></textarea>
                            @error('purpose') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Quantity -->
                            <div>
                                <label for="quantity" class="block text-sm font-medium text-gray-700">Quantity</label>
                                <input type="number" wire:model="quantity" id="quantity" min="1"
                                       class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm">
                                @error('quantity') <span
                                    class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                            </div>

                            <!-- Priority -->
                            <div>
                                <label for="priority" class="block text-sm font-medium text-gray-700">Priority</label>
                                <select wire:model="priority" id="priority"
                                        class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm">
                                    <option value="low">Low</option>
                                    <option value="medium">Medium</option>
                                    <option value="high">High</option>
                                </select>
                                @error('priority') <span
                                    class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Required From -->
                            <div>
                                <label for="required_from" class="block text-sm font-medium text-gray-700">Required
                                    From</label>
                                <input type="date" wire:model="required_from" id="required_from"
                                       class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm">
                                @error('required_from') <span
                                    class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                            </div>

                            <!-- Required Until -->
                            <div>
                                <label for="required_until" class="block text-sm font-medium text-gray-700">Required
                                    Until</label>
                                <input type="date" wire:model="required_until" id="required_until"
                                       class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm">
                                @error('required_until') <span
                                    class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <!-- Notes -->
                        <div>
                            <label for="notes" class="block text-sm font-medium text-gray-700">Additional Notes</label>
                            <textarea wire:model="notes" id="notes" rows="3"
                                      class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm"
                                      placeholder="Any additional information..."></textarea>
                            @error('notes') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>

                        <div class="flex justify-end">
                            <button type="submit"
                                    class="inline-flex items-center px-4 py-2 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-emerald-600 hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500">
                                Submit Request
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Currently Assigned Assets -->
                <div class="bg-white shadow-sm rounded-lg p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Currently Assigned Assets</h3>
                    <div class="space-y-4">
                        @forelse($assignedAssets as $asset)
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-gray-900">{{ $asset->name }}</p>
                                    <p class="text-sm text-gray-500">{{ $asset->category->name }}</p>
                                </div>
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800">
                                    Assigned
                                </span>
                            </div>
                        @empty
                            <p class="text-sm text-gray-500">No assets currently assigned</p>
                        @endforelse
                    </div>
                </div>

                <!-- Recent Requests -->
                <div class="bg-white shadow-sm rounded-lg p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Recent Requests</h3>
                    <div class="space-y-4">
                        @forelse($recentRequests as $request)
                            <div class="border-b border-gray-200 pb-4 last:border-0 last:pb-0">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">{{ $request->category->name }}</p>
                                        <p class="text-xs text-gray-500">Quantity: {{ $request->quantity }}</p>
                                    </div>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        @if($request->status === 'approved') bg-green-100 text-green-800
                                        @elseif($request->status === 'rejected') bg-red-100 text-red-800
                                        @else bg-gray-100 text-gray-800
                                        @endif">
                                        {{ ucfirst($request->status) }}
                                    </span>
                                </div>
                                @if($request->approver)
                                    <p class="mt-1 text-xs text-gray-500">
                                        Processed by {{ $request->approver->name }}
                                    </p>
                                @endif
                            </div>
                        @empty
                            <p class="text-sm text-gray-500">No recent requests</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Notification -->
    <div x-data="{ show: false, message: '' }"
         @request-submitted.window="show = true; message = 'Request submitted successfully!'"
         @request-cancelled.window="show = true; message = 'Request cancelled successfully!'"
         x-show="show"
         x-init="setTimeout(() => show = false, 3000)"
         class="fixed bottom-4 right-4">
        <div class="bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg">
            <span x-text="message"></span>
        </div>
    </div>

    <!-- Asset Requirements Modal -->
    <div x-data="{ show: false, category: null }"
         @category-selected.window="
           show = true;
           category = $event.detail;
        "
         x-show="show"
         x-cloak
         class="fixed inset-0 z-50 overflow-y-auto"
         x-transition:enter="ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">

        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>

        <div class="fixed inset-0 z-10 overflow-y-auto">
            <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                <div
                    class="relative transform overflow-hidden rounded-lg bg-white px-4 pb-4 pt-5 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg sm:p-6">
                    <div class="absolute right-0 top-0 hidden pr-4 pt-4 sm:block">
                        <button @click="show = false" type="button"
                                class="rounded-md bg-white text-gray-400 hover:text-gray-500">
                            <span class="sr-only">Close</span>
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                 stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>

                    <div class="sm:flex sm:items-start">
                        <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
                            <h3 class="text-lg font-semibold leading-6 text-gray-900">
                                Asset Requirements & Information
                            </h3>

                            <div class="mt-4 space-y-4">
                                <div>
                                    <h4 class="text-sm font-medium text-gray-900">Requirements:</h4>
                                    <ul class="mt-2 list-disc pl-5 text-sm text-gray-600 space-y-1">
                                        <li x-show="category?.requires_maintenance">
                                            Regular maintenance required every <span
                                                x-text="category?.maintenance_frequency"></span> days
                                        </li>
                                        <li>
                                            Department manager approval required
                                        </li>
                                        <li x-show="category?.depreciation_rate">
                                            Asset depreciates at <span x-text="category?.depreciation_rate"></span>% per
                                            year
                                        </li>
                                    </ul>
                                </div>

                                <div>
                                    <h4 class="text-sm font-medium text-gray-900">Notes:</h4>
                                    <p class="mt-1 text-sm text-gray-600"
                                       x-text="category?.description || 'No additional notes'"></p>
                                </div>

                                <div>
                                    <h4 class="text-sm font-medium text-gray-900">Current Availability:</h4>
                                    <p class="mt-1 text-sm text-gray-600">
                                        <span x-text="category?.available_count || 0"></span> assets available
                                    </p>
                                </div>

                                <div class="mt-6 flex justify-end">
                                    <button @click="show = false"
                                            class="inline-flex items-center px-4 py-2 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-emerald-600 hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500">
                                        Got it
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

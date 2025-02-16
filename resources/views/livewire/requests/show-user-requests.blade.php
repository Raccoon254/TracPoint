<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-bold text-gray-900">My Assets & Requests</h2>
        </div>

        <!-- Filters -->
        <div class="bg-white rounded-lg shadow-sm p-6 mb-6 border border-gray-100">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- Search -->
                <div class="col-span-2">
                    <label for="search" class="block text-sm font-medium text-gray-700">Search</label>
                    <div class="mt-1 relative rounded-lg">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i data-lucide="search" class="h-4 w-4 text-gray-400"></i>
                        </div>
                        <input type="text" wire:model.live="search"
                               class="pl-10 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm"
                               placeholder="Search by name or asset code...">
                    </div>
                </div>

                <!-- Status Filter -->
                <div>
                    <label for="statusFilter" class="block text-sm font-medium text-gray-700">Status</label>
                    <select wire:model.live="statusFilter"
                            class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm">
                        <option value="">All Statuses</option>
                        <option value="pending">Pending</option>
                        <option value="approved">Approved</option>
                        <option value="rejected">Rejected</option>
                        <option value="fulfilled">Fulfilled</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Tabs -->
        <div class="bg-white rounded-lg shadow-sm mb-6 border border-gray-100">
            <div class="border-b border-gray-200">
                <nav class="-mb-px flex space-x-8 px-6" aria-label="Tabs">
                    <button wire:click="setActiveTab('assets')"
                            class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm
                                {{ $activeTab === 'assets'
                                    ? 'border-emerald-500 text-emerald-600'
                                    : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'
                                }}">
                        <i data-lucide="box" class="w-4 h-4 inline-block mr-2"></i>
                        Assigned Assets
                    </button>
                    <button wire:click="setActiveTab('requests')"
                            class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm
                                {{ $activeTab === 'requests'
                                    ? 'border-emerald-500 text-emerald-600'
                                    : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'
                                }}">
                        <i data-lucide="file-text" class="w-4 h-4 inline-block mr-2"></i>
                        My Requests
                    </button>
                </nav>
            </div>
        </div>

        <!-- Content -->
        <div>
            @if($activeTab !== 'requests')
                @include('livewire.requests.partials.user-assets-list')
            @else
                @include('livewire.requests.partials.user-requests-list')
            @endif
        </div>
    </div>

    <!-- Return Asset Modal -->
    <div x-data="{ show: @entangle('showReturnModal') }"
         x-show="show"
         x-cloak
         class="fixed inset-0 z-50 overflow-y-auto"
         aria-labelledby="modal-title"
         role="dialog"
         aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="show"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"
                 aria-hidden="true"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div x-show="show"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 class="inline-block align-bottom bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6">
                @if($selectedAsset)
                    <div>
                        <div class="flex justify-between items-start">
                            <h3 class="text-lg leading-6 font-medium text-gray-900">
                                Return Asset
                            </h3>
                            <button type="button" wire:click="$set('showReturnModal', false)"
                                    class="bg-white rounded-md text-gray-400 hover:text-gray-500 focus:outline-none">
                                <i data-lucide="x" class="h-6 w-6"></i>
                            </button>
                        </div>

                        <div class="mt-4">
                            <div class="space-y-4">
                                <div>
                                    <label for="returnCondition" class="block text-sm font-medium text-gray-700">
                                        Asset Condition
                                    </label>
                                    <select wire:model="returnCondition"
                                            class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm">
                                        <option value="">Select Condition</option>
                                        <option value="good">Good</option>
                                        <option value="fair">Fair</option>
                                        <option value="poor">Poor</option>
                                    </select>
                                    @error('returnCondition')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="returnNotes" class="block text-sm font-medium text-gray-700">
                                        Return Notes
                                    </label>
                                    <textarea wire:model="returnNotes"
                                              rows="3"
                                              class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm"
                                              placeholder="Please provide any relevant notes about the asset's condition or return..."></textarea>
                                    @error('returnNotes')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mt-6 flex justify-end space-x-3">
                            <button type="button"
                                    wire:click="$set('showReturnModal', false)"
                                    class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500">
                                Cancel
                            </button>
                            <button type="button"
                                    wire:click="returnAsset"
                                    class="inline-flex items-center px-4 py-2 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-emerald-600 hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500">
                                Confirm Return
                            </button>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Notification -->
    <div x-data="{ show: false, message: '' }"
         @asset-returned.window="show = true; message = $event.detail; setTimeout(() => show = false, 3000)"
         x-show="show"
         x-transition
         class="fixed bottom-4 right-4">
        <div class="bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg">
            <span x-text="message"></span>
        </div>
    </div>
</div>

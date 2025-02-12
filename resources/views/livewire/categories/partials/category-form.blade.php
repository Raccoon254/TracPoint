<div class="fixed inset-0 bg-black bg-opacity-50"></div>
<div class="fixed inset-0 z-50 overflow-y-auto">
    <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
        <div class="relative transform overflow-hidden rounded-lg bg-white px-4 pb-4 pt-5 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg sm:p-6">
            <div class="absolute right-0 top-0 hidden pr-4 pt-4 sm:block">
                <button wire:click="$set('show{{ ucfirst($mode) }}Modal', false)" type="button" class="rounded-md bg-white text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2">
                    <span class="sr-only">Close</span>
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <div class="sm:flex sm:items-start">
                <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
                    <h3 class="text-lg font-semibold leading-6 text-gray-900">
                        {{ $mode === 'create' ? 'Create Category' : 'Edit Category' }}
                    </h3>

                    <form wire:submit="{{ $mode === 'create' ? 'createCategory' : 'updateCategory' }}" class="mt-6 space-y-4">
                        <!-- Name -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                            <input type="text" wire:model.live="name" id="name"
                                   class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm">
                            @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <!-- Description -->
                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                            <textarea wire:model.live="description" id="description" rows="3"
                                      class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm"></textarea>
                            @error('description') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <!-- Parent Category -->
                        <div>
                            <label for="parent_id" class="block text-sm font-medium text-gray-700">Parent Category</label>
                            <select wire:model.live="parent_id" id="parent_id"
                                    class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm">
                                <option value="">None</option>
                                @foreach($parentCategories as $parentCategory)
                                    @if(!isset($categoryId) || $parentCategory->id !== $categoryId)
                                        <option value="{{ $parentCategory->id }}">{{ $parentCategory->name }}</option>
                                    @endif
                                @endforeach
                            </select>
                            @error('parent_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <!-- Depreciation Rate -->
                        <div>
                            <label for="depreciation_rate" class="block text-sm font-medium text-gray-700">
                                Depreciation Rate (%)
                            </label>
                            <x-text-input type="number" wire:model.live="depreciation_rate" id="depreciation_rate"
                                   class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm"
                                   min="0" max="100" step="0.01" />
                            @error('depreciation_rate') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <!-- Maintenance Requirements -->
                        <div class="space-y-4">
                            <div class="flex items-center">
                                <input type="checkbox" wire:model.live="requires_maintenance" id="requires_maintenance"
                                       class="rounded border-gray-300 text-emerald-600 focus:ring-emerald-500">
                                <label for="requires_maintenance" class="ml-2 block text-sm font-medium text-gray-700">
                                    Requires Maintenance
                                </label>
                            </div>

                            @if($requires_maintenance)
                                <div>
                                    <label for="maintenance_frequency" class="block text-sm font-medium text-gray-700">
                                        Maintenance Frequency (days)
                                    </label>
                                    <input type="number" wire:model.live="maintenance_frequency" id="maintenance_frequency"
                                           class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm"
                                           min="1">
                                    @error('maintenance_frequency') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                            @endif
                        </div>

                        <div class="mt-6 flex justify-end space-x-3">
                            <button type="button"
                                    wire:click="$set('show{{ ucfirst($mode) }}Modal', false)"
                                    class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500">
                                Cancel
                            </button>
                            <button type="submit"
                                    class="inline-flex items-center px-4 py-2 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-emerald-600 hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500">
                                {{ $mode === 'create' ? 'Create Category' : 'Update Category' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

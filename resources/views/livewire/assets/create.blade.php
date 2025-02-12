<div class="py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-8">
            <h2 class="text-3xl font-bold text-gray-900">{{ $step === 1 && $categoryMode ? 'Create Category' : 'Create Asset' }}</h2>
            <p class="mt-2 text-gray-600">Step {{ $step }} of 5</p>
        </div>

        <!-- Progress Bar -->
        <div class="w-full bg-gray-200 rounded-full h-2.5 mb-8">
            <div class="bg-emerald-600 h-2.5 rounded-full transition-all duration-500"
                 style="width: {{ ($step * 20) }}%"></div>
        </div>

        <!-- Step 1: Category Selection -->
        @if ($step === 1)
            <div class="bg-white rounded-xl shadow-sm p-6">
                @if (!$categoryMode)
                    <div class="space-y-6">
                        <div>
                            <label for="category_id" class="block text-sm font-medium text-gray-700">Select
                                Category</label>
                            <select wire:model="category_id" id="category_id"
                                    class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                                <option value="">Select a category</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                            @error('category_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div class="flex justify-between items-center">
                            <button type="button" wire:click="$set('categoryMode', true)"
                                    class="text-emerald-600 hover:text-emerald-500">
                                Create New Category
                            </button>
                            <x-primary-button wire:click="nextStep">Next</x-primary-button>
                        </div>
                    </div>
                @else
                    <form wire:submit.prevent="createCategory" class="space-y-6">
                        <!-- Category Name -->
                        <div>
                            <label for="category_name" class="block text-sm font-medium text-gray-700">Category
                                Name</label>
                            <input type="text" wire:model="category_name" id="category_name"
                                   class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                            @error('category_name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <!-- Category Description -->
                        <div>
                            <label for="category_description" class="block text-sm font-medium text-gray-700">Description</label>
                            <textarea wire:model="category_description" id="category_description" rows="3"
                                      class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500"></textarea>
                            @error('category_description') <span
                                class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <!-- Parent Category -->
                        <div>
                            <label for="parent_category_id" class="block text-sm font-medium text-gray-700">Parent
                                Category</label>
                            <select wire:model="parent_category_id" id="parent_category_id"
                                    class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                                <option value="">None</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Depreciation Rate -->
                        <div>
                            <label for="depreciation_rate" class="block text-sm font-medium text-gray-700">Depreciation
                                Rate (%)</label>
                            <input type="number" wire:model="depreciation_rate" id="depreciation_rate" step="0.01"
                                   class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                            @error('depreciation_rate') <span
                                class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <!-- Maintenance Requirements -->
                        <div>
                            <label class="flex items-center">
                                <input type="checkbox" wire:model="requires_maintenance"
                                       class="rounded border-gray-300 text-emerald-600 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                                <span class="ml-2 text-sm text-gray-600">Requires Maintenance</span>
                            </label>
                        </div>

                        @if($requires_maintenance)
                            <div>
                                <label for="maintenance_frequency" class="block text-sm font-medium text-gray-700">
                                    Maintenance Frequency (days)
                                </label>
                                <input type="number" wire:model="maintenance_frequency" id="maintenance_frequency"
                                       class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                            </div>
                        @endif

                        <div class="flex justify-between items-center">
                            <button type="button" wire:click="$set('categoryMode', false)"
                                    class="text-gray-600 hover:text-gray-500">
                                Cancel
                            </button>
                            <x-primary-button type="submit">Create Category</x-primary-button>
                        </div>
                    </form>
                @endif
            </div>
        @endif

        <!-- Step 2: Basic Asset Information -->
        @if ($step === 2)
            <div class="bg-white rounded-xl shadow-sm p-6 space-y-6">
                <!-- Name -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">Asset Name</label>
                    <input type="text" wire:model="name" id="name"
                           class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                    @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <!-- Description -->
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                    <textarea wire:model="description" id="description" rows="3"
                              class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500"></textarea>
                    @error('description') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <!-- Model -->
                <div>
                    <label for="model" class="block text-sm font-medium text-gray-700">Model</label>
                    <input type="text" wire:model="model" id="model"
                           class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                    @error('model') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <!-- Manufacturer -->
                <div>
                    <label for="manufacturer" class="block text-sm font-medium text-gray-700">Manufacturer</label>
                    <input type="text" wire:model="manufacturer" id="manufacturer"
                           class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                    @error('manufacturer') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <!-- Serial Number -->
                <div>
                    <label for="serial_number" class="block text-sm font-medium text-gray-700">Serial Number</label>
                    <input type="text" wire:model="serial_number" id="serial_number"
                           class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                    @error('serial_number') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <!-- Barcode -->
                <div>
                    <label for="barcode" class="block text-sm font-medium text-gray-700">Barcode</label>
                    <input type="text" wire:model="barcode" id="barcode"
                           class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                    @error('barcode') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <!-- Is Mobile -->
                <div>
                    <label class="flex items-center">
                        <input type="checkbox" wire:model="is_mobile"
                               class="rounded border-gray-300 text-emerald-600 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                        <span class="ml-2 text-sm text-gray-600">Is Mobile Asset</span>
                    </label>
                </div>

                <div class="flex justify-between items-center pt-4">
                    <button type="button" wire:click="previousStep"
                            class="text-gray-600 hover:text-gray-500">
                        Previous
                    </button>
                    <x-primary-button wire:click="nextStep">Next</x-primary-button>
                </div>

                <!-- Preview Images -->
                @if ($primary_image)
                    <div class="mt-4">
                        <h4 class="text-sm font-medium text-gray-700 mb-2">Primary Image Preview</h4>
                        <img src="{{ $primary_image->temporaryUrl() }}"
                             class="w-32 h-32 object-cover rounded-lg">
                    </div>
                @endif

                @if ($additional_images)
                    <div class="mt-4">
                        <h4 class="text-sm font-medium text-gray-700 mb-2">Additional Images Preview</h4>
                        <div class="grid grid-cols-4 gap-4">
                            @foreach($additional_images as $image)
                                <img src="{{ $image->temporaryUrl() }}"
                                     class="w-24 h-24 object-cover rounded-lg">
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        @endif

        <!-- Step 3: Financial Information -->
        @if ($step === 3)
            <div class="bg-white rounded-xl shadow-sm p-6 space-y-6">
                <!-- Value -->
                <div>
                    <label for="value" class="block text-sm font-medium text-gray-700">Value</label>
                    <div class="mt-1 relative rounded-lg shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="text-gray-500 sm:text-sm">$</span>
                        </div>
                        <input type="number" wire:model="value" id="value" step="0.01"
                               class="pl-7 block w-full rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                    </div>
                    @error('value') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <!-- Purchase Date -->
                <div>
                    <label for="purchase_date" class="block text-sm font-medium text-gray-700">Purchase Date</label>
                    <input type="date" wire:model="purchase_date" id="purchase_date"
                           class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                    @error('purchase_date') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <!-- Warranty Expiry -->
                <div>
                    <label for="warranty_expiry" class="block text-sm font-medium text-gray-700">Warranty Expiry</label>
                    <input type="date" wire:model="warranty_expiry" id="warranty_expiry"
                           class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                    @error('warranty_expiry') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <!-- Asset Depreciation Rate -->
                <div>
                    <label for="depreciation_rate_asset" class="block text-sm font-medium text-gray-700">Depreciation
                        Rate (%)</label>
                    <input type="number" wire:model="depreciation_rate_asset" id="depreciation_rate_asset" step="0.01"
                           class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                    @error('depreciation_rate_asset') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div class="flex justify-between items-center pt-4">
                    <button type="button" wire:click="previousStep"
                            class="text-gray-600 hover:text-gray-500">
                        Previous
                    </button>
                    <x-primary-button wire:click="nextStep">Next</x-primary-button>
                </div>
            </div>
        @endif

        <!-- Step 4: Assignment Information -->
        @if ($step === 4)
            <div class="bg-white rounded-xl shadow-sm p-6 space-y-6">
                <!-- Status -->
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                    <select wire:model="status" id="status"
                            class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                        @foreach($statusOptions as $option)
                            <option value="{{ $option }}">{{ ucfirst($option) }}</option>
                        @endforeach
                    </select>
                    @error('status') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <!-- Condition -->
                <div>
                    <label for="condition" class="block text-sm font-medium text-gray-700">Condition</label>
                    <select wire:model="condition" id="condition"
                            class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                        @foreach($conditionOptions as $option)
                            <option value="{{ $option }}">{{ ucfirst($option) }}</option>
                        @endforeach
                    </select>
                    @error('condition') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <!-- Department -->
                <div>
                    <label for="current_department_id"
                           class="block text-sm font-medium text-gray-700">Department</label>
                    <select wire:model="current_department_id" id="current_department_id"
                            class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                        <option value="">Select Department</option>
                        @foreach($departments as $department)
                            <option value="{{ $department->id }}">{{ $department->name }}</option>
                        @endforeach
                    </select>
                    @error('current_department_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <!-- Location -->
                <div>
                    <label for="current_location" class="block text-sm font-medium text-gray-700">Location</label>
                    <input type="text" wire:model="current_location" id="current_location"
                           class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                    @error('current_location') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <!-- Assigned To -->
                <div>
                    <label for="assigned_to" class="block text-sm font-medium text-gray-700">Assigned To</label>
                    <select wire:model="assigned_to" id="assigned_to"
                            class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                        <option value="">Not Assigned</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                        @endforeach
                    </select>
                    @error('assigned_to') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                @if($assigned_to)
                    <!-- Assignment Date -->
                    <div>
                        <label for="assigned_date" class="block text-sm font-medium text-gray-700">Assignment
                            Date</label>
                        <input type="date" wire:model="assigned_date" id="assigned_date"
                               class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                        @error('assigned_date') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <!-- Expected Return Date -->
                    <div>
                        <label for="expected_return_date" class="block text-sm font-medium text-gray-700">Expected
                            Return Date</label>
                        <input type="date" wire:model="expected_return_date" id="expected_return_date"
                               class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                        @error('expected_return_date') <span
                            class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                @endif

                <!-- Notes -->
                <div>
                    <label for="notes" class="block text-sm font-medium text-gray-700">Notes</label>
                    <textarea wire:model="notes" id="notes" rows="3"
                              class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500"></textarea>
                    @error('notes') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div class="flex justify-between items-center pt-4">
                    <button type="button" wire:click="previousStep"
                            class="text-gray-600 hover:text-gray-500">
                        Previous
                    </button>
                    <x-primary-button wire:click="nextStep">Next</x-primary-button>
                </div>
            </div>
        @endif

        <!-- Step 5: Images -->
        @if ($step === 5)
            <div class="bg-white rounded-xl shadow-sm p-6 space-y-6">
                <!-- Primary Image -->
                <div>
                    <label for="primary_image" class="block text-sm font-medium text-gray-700">Primary Image</label>
                    <input type="file" wire:model="primary_image" id="primary_image" accept="image/*"
                           class="mt-1 block w-full text-sm text-gray-500
                          file:mr-4 file:py-2 file:px-4
                          file:rounded-lg file:border-0
                          file:text-sm file:font-semibold
                          file:bg-emerald-50 file:text-emerald-700
                          hover:file:bg-emerald-100">
                    @error('primary_image') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <!-- Additional Images -->
                <div>
                    <label for="additional_images" class="block text-sm font-medium text-gray-700">Additional
                        Images</label>
                    <input type="file" wire:model="additional_images" id="additional_images" accept="image/*" multiple
                           class="mt-1 block w-full text-sm text-gray-500
                          file:mr-4 file:py-2 file:px-4
                          file:rounded-lg file:border-0
                          file:text-sm file:font-semibold
                          file:bg-emerald-50 file:text-emerald-700
                          hover:file:bg-emerald-100">
                    @error('additional_images.*') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div class="flex justify-between items-center pt-4">
                    <button type="button" wire:click="previousStep"
                            class="text-gray-600 hover:text-gray-500">
                        Previous
                    </button>
                    <x-primary-button wire:click="save">
                        <span wire:loading wire:target="save" class="flex items-center justify-center h-5 w-5">
                            <i data-lucide="loader-circle" class="w-5 h-5 animate-spin"></i>
                        </span>
                        <span wire:loading.remove wire:target="save">Create Asset</span>
                    </x-primary-button>
                </div>

                <!-- Preview Images -->
                @if ($primary_image)
                    <div class="mt-4">
                        <h4 class="text-sm font-medium text-gray-700 mb-2">Primary Image Preview</h4>
                        <img src="{{ $primary_image->temporaryUrl() }}"
                                alt="Primary Image"
                             class="w-32 h-32 object-cover rounded-lg border border-gray-200">
                    </div>
                @endif

                @if ($additional_images)
                    <div class="mt-4">
                        <h4 class="text-sm font-medium text-gray-700 mb-2">Additional Images Preview</h4>
                        <div class="flex flex-wrap gap-4">
                            @foreach($additional_images as $image)
                                <img src="{{ $image->temporaryUrl() }}"
                                     alt="Additional Image"
                                     class="w-24 h-24 object-cover rounded-lg border border-gray-200">
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        @endif

        <!-- Success Message -->
        @if (session()->has('message'))
            <div class="fixed bottom-4 right-4">
                <div class="bg-green-50 border-l-4 border-green-400 p-4 rounded-lg shadow-lg">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd"
                                      d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                      clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-green-700">
                                {{ session('message') }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>

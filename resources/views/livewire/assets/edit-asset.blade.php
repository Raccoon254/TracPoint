<div class="py-12"
     x-data="{
        hasUnsavedChanges: @entangle('hasUnsavedChanges'),
        showModal: false,
        pendingPath: null,

        init() {
            // Remove the default browser beforeunload handler
            window.onbeforeunload = null;

            // Custom navigation handling
            window.addEventListener('navigate', (event) => {
                if (this.hasUnsavedChanges) {
                    this.handleNavigation(event);
                }
            });

            // Handle links
            document.addEventListener('click', (event) => {
                const link = event.target.closest('a');
                if (link && this.hasUnsavedChanges && !link.hasAttribute('wire:navigate')) {
                    event.preventDefault();
                    this.pendingPath = link.href;
                    this.showModal = true;
                }
            });

            // Handle browser back/forward
            window.addEventListener('popstate', (event) => {
                if (this.hasUnsavedChanges) {
                    event.preventDefault();
                    this.pendingPath = document.referrer || '/';
                    this.showModal = true;
                    history.pushState(null, '', window.location.href);
                }
            });
        },

        handleNavigation(event) {
            if (this.hasUnsavedChanges) {
                event.preventDefault();
                this.showModal = true;
            }
        },

        confirmNavigation() {
            this.hasUnsavedChanges = false;
            if (this.pendingPath) {
                window.location.href = this.pendingPath;
            }
        },

        cancelNavigation() {
            this.showModal = false;
            this.pendingPath = null;
        }
    }">

    <!-- Add event listeners to all navigation links -->
    <div @click.capture="$event.target.tagName === 'A' && handleNavigation($event)">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-8">
                <h2 class="text-3xl font-bold text-gray-900">Edit Asset: {{ $asset->name }}</h2>
                <p class="mt-2 text-gray-600">Step {{ $step }} of 5</p>
            </div>

            <!-- Progress Bar (same as create) -->
            <div class="w-full bg-gray-200 rounded-full h-2.5 mb-8">
                <div class="bg-emerald-600 h-2.5 rounded-full transition-all duration-500"
                     style="width: {{ ($step * 20) }}%"></div>
            </div>

            <!-- Step 1: Category Selection -->
            @if ($step === 1)
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <div class="space-y-6">
                        <div>
                            <label for="category_id" class="block text-sm font-medium text-gray-700">Category</label>
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
                            <a href="{{ route('assets.show', $asset) }}"
                               class="text-gray-600 hover:text-gray-500">
                                Cancel
                            </a>
                            <x-primary-button wire:click="nextStep">Next</x-primary-button>
                        </div>
                    </div>
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
                        <label for="warranty_expiry" class="block text-sm font-medium text-gray-700">Warranty
                            Expiry</label>
                        <input type="date" wire:model="warranty_expiry" id="warranty_expiry"
                               class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                        @error('warranty_expiry') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <!-- Asset Depreciation Rate -->
                    <div>
                        <label for="depreciation_rate_asset" class="block text-sm font-medium text-gray-700">Depreciation
                            Rate (%)</label>
                        <input type="number" wire:model="depreciation_rate_asset" id="depreciation_rate_asset"
                               step="0.01"
                               class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                        @error('depreciation_rate_asset') <span
                            class="text-red-500 text-sm">{{ $message }}</span> @enderror
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
                        @error('current_department_id') <span
                            class="text-red-500 text-sm">{{ $message }}</span> @enderror
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

            <!-- Modification for Step 5 (Images) -->
            @if ($step === 5)
                <div class="bg-white rounded-xl shadow-sm p-6 space-y-6">
                    <!-- Existing Images -->
                    @if(count($existing_images) > 0)
                        <div class="mb-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Existing Images</h3>
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                @foreach($existing_images as $image)
                                    <div class="relative group">
                                        <img src="{{ Storage::url($image['image_path']) }}"
                                             class="w-full h-32 object-cover rounded-lg border border-gray-200">
                                        @if($image['image_type'] === 'primary')
                                            <span
                                                class="absolute top-2 left-2 px-2 py-1 text-xs font-semibold bg-emerald-100 text-emerald-800 rounded-md">
                                            Primary
                                        </span>
                                        @endif
                                        <button type="button"
                                                wire:click="removeExistingImage({{ $image['id'] }})"
                                                class="absolute top-2 right-2 p-1 bg-red-100 text-red-600 rounded-full opacity-0 group-hover:opacity-100 transition-opacity">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                        </button>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- New Image Upload Fields (same as create) -->
                    <div>
                        <label for="primary_image" class="block text-sm font-medium text-gray-700">New Primary
                            Image</label>
                        <input type="file" wire:model="primary_image" id="primary_image" accept="image/*"
                               class="mt-1 block w-full text-sm text-gray-500
                              file:mr-4 file:py-2 file:px-4
                              file:rounded-lg file:border-0
                              file:text-sm file:font-semibold
                              file:bg-emerald-50 file:text-emerald-700
                              hover:file:bg-emerald-100">
                        @error('primary_image') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label for="additional_images" class="block text-sm font-medium text-gray-700">Additional
                            Images</label>
                        <input type="file" wire:model="additional_images" id="additional_images" accept="image/*"
                               multiple
                               class="mt-1 block w-full text-sm text-gray-500
                              file:mr-4 file:py-2 file:px-4
                              file:rounded-lg file:border-0
                              file:text-sm file:font-semibold
                              file:bg-emerald-50 file:text-emerald-700
                              hover:file:bg-emerald-100">
                        @error('additional_images.*') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <!-- New Image Previews -->
                    @if ($primary_image)
                        <div class="mt-4">
                            <h4 class="text-sm font-medium text-gray-700 mb-2">New Primary Image Preview</h4>
                            <img src="{{ $primary_image->temporaryUrl() }}"
                                 class="w-32 h-32 object-cover rounded-lg border border-gray-200">
                        </div>
                    @endif

                    @if ($additional_images)
                        <div class="mt-4">
                            <h4 class="text-sm font-medium text-gray-700 mb-2">New Additional Images Preview</h4>
                            <div class="grid grid-cols-4 gap-4">
                                @foreach($additional_images as $image)
                                    <img src="{{ $image->temporaryUrl() }}"
                                         class="w-24 h-24 object-cover rounded-lg border border-gray-200">
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <div class="flex justify-between items-center pt-4">
                        <button type="button" wire:click="previousStep"
                                class="text-gray-600 hover:text-gray-500">
                            Previous
                        </button>
                        <x-primary-button wire:click="save">
                        <span wire:loading wire:target="save" class="flex items-center justify-center h-5 w-5">
                            <i data-lucide="loader-circle" class="w-5 h-5 animate-spin"></i>
                        </span>
                            <span wire:loading.remove wire:target="save">Update Asset</span>
                        </x-primary-button>
                    </div>
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

            <!-- Confirmation Modal for Leaving Page -->
            <div x-show="showModal"
                 x-cloak
                 class="fixed inset-0 z-50 overflow-y-auto"
                 aria-labelledby="modal-title"
                 role="dialog"
                 aria-modal="true">
                <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                    <!-- Overlay -->
                    <div x-show="showModal"
                         x-transition:enter="ease-out duration-300"
                         x-transition:enter-start="opacity-0"
                         x-transition:enter-end="opacity-100"
                         x-transition:leave="ease-in duration-200"
                         x-transition:leave-start="opacity-100"
                         x-transition:leave-end="opacity-0"
                         class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"
                         @click="cancelNavigation()"
                         aria-hidden="true"></div>

                    <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                    <!-- Modal panel -->
                    <div x-show="showModal"
                         x-transition:enter="ease-out duration-300"
                         x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                         x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                         x-transition:leave="ease-in duration-200"
                         x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                         x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                         class="inline-block align-bottom bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6">
                        <div class="sm:flex sm:items-start">
                            <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-yellow-100 sm:mx-0 sm:h-10 sm:w-10">
                                <svg class="h-6 w-6 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                </svg>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                    Unsaved Changes
                                </h3>
                                <div class="mt-2">
                                    <p class="text-sm text-gray-500">
                                        You have unsaved changes. Do you want to leave this page? All changes will be lost.
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse">
                            <button type="button"
                                    class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm"
                                    @click="confirmNavigation()">
                                Leave Page
                            </button>
                            <button type="button"
                                    class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 sm:mt-0 sm:w-auto sm:text-sm"
                                    @click="cancelNavigation()">
                                Stay on Page
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

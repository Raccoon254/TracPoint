<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="md:flex md:items-center md:justify-between mb-6">
            <div class="min-w-0 flex-1">
                <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:truncate sm:text-3xl sm:tracking-tight">
                    Create New Audit
                </h2>
                <p class="mt-1 text-sm text-gray-500">
                    Complete the steps below to perform an asset audit
                </p>
            </div>
        </div>

        <!-- Step Progress -->
        <div class="bg-white rounded-xl shadow-sm p-4 mb-6">
            <div class="flex items-center">
                @for ($i = 1; $i <= $totalSteps; $i++)
                    <div class="flex items-center {{ $i < $totalSteps ? 'flex-1' : '' }}">
                        <div class="flex-shrink-0">
                            <span class="h-8 w-8 flex items-center justify-center rounded-full
                                {{ $currentStep > $i ? 'bg-emerald-500 text-white' : ($currentStep == $i ? 'bg-emerald-600 text-white' : 'bg-gray-200 text-gray-700') }}">
                                {{ $i }}
                            </span>
                        </div>
                        <div class="ml-4 {{ $i < $totalSteps ? 'flex-1' : '' }}">
                            <p class="text-sm font-medium
                                {{ $currentStep > $i ? 'text-emerald-500' : ($currentStep == $i ? 'text-emerald-600' : 'text-gray-500') }}">
                                {{ $stepTitles[$i] }}
                            </p>
                        </div>

                        @if ($i < $totalSteps)
                            <div class="flex-1 h-0.5 mx-2 {{ $currentStep > $i ? 'bg-emerald-500' : 'bg-gray-200' }}"></div>
                        @endif
                    </div>
                @endfor
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Step 1: Asset Selection -->
                @if ($currentStep === 1)
                    <div class="bg-white rounded-xl shadow-sm p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Select Asset to Audit</h3>

                        <!-- Search and Filters -->
                        <div class="space-y-4 mb-6">
                            <div>
                                <label for="searchQuery" class="block text-sm font-medium text-gray-700">Search Assets</label>
                                <div class="mt-1 relative rounded-md shadow-sm">
                                    <input type="text" wire:model.live.debounce.300ms="searchQuery" id="searchQuery"
                                           class="focus:ring-emerald-500 focus:border-emerald-500 block w-full pr-10 sm:text-sm border-gray-300 rounded-md"
                                           placeholder="Search by name, asset code, or serial number...">
                                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="departmentFilter" class="block text-sm font-medium text-gray-700">Department</label>
                                    <select wire:model.live="departmentFilter" id="departmentFilter"
                                            class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm">
                                        <option value="">All Departments</option>
                                        @foreach($departments as $department)
                                            <option value="{{ $department->id }}">{{ $department->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div>
                                    <label for="categoryFilter" class="block text-sm font-medium text-gray-700">Category</label>
                                    <select wire:model.live="categoryFilter" id="categoryFilter"
                                            class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm">
                                        <option value="">All Categories</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Search Results -->
                        <div class="border rounded-lg overflow-hidden">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Asset</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Department</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Condition</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                                </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($searchResults as $asset)
                                    <tr class="{{ $selectedAssetId === $asset->id ? 'bg-emerald-50' : '' }}">
                                        <td class="px-6 py-4">
                                            <div class="flex items-center">
                                                <div>
                                                    <div class="text-sm font-medium text-gray-900">
                                                        {{ $asset->name }}
                                                    </div>
                                                    <div class="text-sm text-gray-500">
                                                        {{ $asset->asset_code }}
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="text-sm text-gray-900">{{ $asset->department->name ?? 'N/A' }}</div>
                                            <div class="text-sm text-gray-500">{{ $asset->category->name }}</div>
                                        </td>
                                        <td class="px-6 py-4">
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                                    @if($asset->condition === 'new') bg-green-100 text-green-800
                                                    @elseif($asset->condition === 'good') bg-blue-100 text-blue-800
                                                    @elseif($asset->condition === 'fair') bg-yellow-100 text-yellow-800
                                                    @else bg-red-100 text-red-800
                                                    @endif">
                                                    {{ ucfirst($asset->condition) }}
                                                </span>
                                        </td>
                                        <td class="px-6 py-4 text-sm font-medium">
                                            <button wire:click="selectAsset({{ $asset->id }})"
                                                    class="text-emerald-600 hover:text-emerald-900 {{ $selectedAssetId === $asset->id ? 'font-bold' : '' }}">
                                                {{ $selectedAssetId === $asset->id ? 'Selected' : 'Select' }}
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-4 text-center text-sm text-gray-500">
                                            @if(strlen($searchQuery) < 2)
                                                Type at least 2 characters to search
                                            @else
                                                No assets found matching your search
                                            @endif
                                        </td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>

                        <!-- Assets Due for Audit -->
                        <div class="mt-8">
                            <h4 class="text-sm font-medium text-gray-900 mb-2">Assets Due for Audit</h4>
                            <div class="border rounded-lg overflow-hidden">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Asset</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Department</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                                    </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                    @forelse($assetsDueForAudit as $asset)
                                        <tr>
                                            <td class="px-6 py-4">
                                                <div class="flex items-center">
                                                    <div>
                                                        <div class="text-sm font-medium text-gray-900">
                                                            {{ $asset->name }}
                                                        </div>
                                                        <div class="text-sm text-gray-500">
                                                            {{ $asset->asset_code }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4">
                                                <div class="text-sm text-gray-900">{{ $asset->department->name ?? 'N/A' }}</div>
                                                <div class="text-sm text-gray-500">{{ $asset->category->name }}</div>
                                            </td>
                                            <td class="px-6 py-4 text-sm font-medium">
                                                <button wire:click="selectAsset({{ $asset->id }})" class="text-emerald-600 hover:text-emerald-900">
                                                    Select
                                                </button>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="px-6 py-4 text-center text-sm text-gray-500">
                                                No assets are currently due for audit
                                            </td>
                                        </tr>
                                    @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Step 2: Location Verification -->
                @if ($currentStep === 2)
                    <div class="bg-white rounded-xl shadow-sm p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Verify Asset Location</h3>

                        <!-- Asset Summary -->
                        <div class="bg-gray-50 p-4 rounded-lg mb-6">
                            <h4 class="text-sm font-medium text-gray-900">Selected Asset</h4>
                            <div class="flex justify-between items-center mt-2">
                                <div>
                                    <p class="text-base font-medium text-gray-900">{{ $selectedAsset->name }}</p>
                                    <p class="text-sm text-gray-500">{{ $selectedAsset->asset_code }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm text-gray-500">Department: {{ $selectedAsset->department->name ?? 'N/A' }}</p>
                                    <p class="text-sm text-gray-500">Category: {{ $selectedAsset->category->name }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Registered Location -->
                        <div class="mb-6">
                            <h4 class="text-sm font-medium text-gray-900 mb-2">Registered Location</h4>
                            <div class="bg-gray-100 p-3 rounded-lg">
                                <p class="text-sm text-gray-900">{{ $selectedAsset->current_location ?: 'No location registered' }}</p>
                            </div>
                        </div>

                        <!-- Verification Form -->
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Is the asset in the registered location?</label>
                                <div class="mt-2 space-x-4">
                                    <label class="inline-flex items-center">
                                        <input type="radio" wire:model="locationVerified" value="1" class="focus:ring-emerald-500 h-4 w-4 text-emerald-600 border-gray-300">
                                        <span class="ml-2 text-sm text-gray-700">Yes</span>
                                    </label>
                                    <label class="inline-flex items-center">
                                        <input type="radio" wire:model="locationVerified" value="0" class="focus:ring-emerald-500 h-4 w-4 text-emerald-600 border-gray-300">
                                        <span class="ml-2 text-sm text-gray-700">No</span>
                                    </label>
                                </div>
                                @error('locationVerified') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                            </div>

                            @if($locationVerified === false)
                                <div>
                                    <label for="actualLocation" class="block text-sm font-medium text-gray-700">Actual Location</label>
                                    <input type="text" wire:model="actualLocation" id="actualLocation"
                                           class="mt-1 focus:ring-emerald-500 focus:border-emerald-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                           placeholder="Enter the current location of the asset">
                                    @error('actualLocation') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                </div>
                            @endif

                            <div>
                                <label for="locationNotes" class="block text-sm font-medium text-gray-700">Location Notes</label>
                                <textarea wire:model="locationNotes" id="locationNotes" rows="3"
                                          class="mt-1 focus:ring-emerald-500 focus:border-emerald-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                          placeholder="Any additional notes about the asset's location (optional)"></textarea>
                                @error('locationNotes') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Step 3: Condition Assessment -->
                <!-- Step 3: Condition Assessment -->
                @if ($currentStep === 3)
                    <div class="bg-white rounded-xl shadow-sm p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Assess Asset Condition</h3>

                        <!-- Asset Summary -->
                        <div class="bg-gray-50 p-4 rounded-lg mb-6">
                            <h4 class="text-sm font-medium text-gray-900">Selected Asset</h4>
                            <div class="flex justify-between items-center mt-2">
                                <div>
                                    <p class="text-base font-medium text-gray-900">{{ $selectedAsset->name }}</p>
                                    <p class="text-sm text-gray-500">{{ $selectedAsset->asset_code }}</p>
                                </div>
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                    @if($previousCondition === 'new') bg-green-100 text-green-800
                                    @elseif($previousCondition === 'good') bg-blue-100 text-blue-800
                                    @elseif($previousCondition === 'fair') bg-yellow-100 text-yellow-800
                                    @else bg-red-100 text-red-800
                                    @endif">
                                    Previous: {{ ucfirst($previousCondition) }}
                                </span>
                            </div>
                        </div>

                        <!-- Condition Assessment Form -->
                        <div class="space-y-6">
                            <div>
                                <label for="newCondition" class="block text-sm font-medium text-gray-700">Current Condition</label>
                                <select wire:model="newCondition" id="newCondition"
                                        class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm">
                                    @foreach($conditionOptions as $condition)
                                        <option value="{{ $condition }}">{{ ucfirst($condition) }}</option>
                                    @endforeach
                                </select>
                                @error('newCondition') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label for="discrepancies" class="block text-sm font-medium text-gray-700">Discrepancies</label>
                                <textarea wire:model="discrepancies" id="discrepancies" rows="3"
                                          class="mt-1 focus:ring-emerald-500 focus:border-emerald-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                          placeholder="Describe any issues or discrepancies found (if any)"></textarea>
                                @error('discrepancies') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Upload Images</label>
                                <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md">
                                    <div class="space-y-1 text-center">
                                        <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                        <div class="flex text-sm text-gray-600">
                                            <label for="images" class="relative cursor-pointer bg-white rounded-md font-medium text-emerald-600 hover:text-emerald-500 focus-within:outline-none">
                                                <span>Upload images</span>
                                                <input id="images" wire:model="images" type="file" multiple class="sr-only">
                                            </label>
                                            <p class="pl-1">or drag and drop</p>
                                        </div>
                                        <p class="text-xs text-gray-500">
                                            PNG, JPG, GIF up to 5MB
                                        </p>
                                    </div>
                                </div>
                                @error('images.*') <span class="text-red-500 text-xs block mt-1">{{ $message }}</span> @enderror

                                <!-- Preview uploaded images -->
                                @if (!empty($images))
                                    <div class="mt-2 grid grid-cols-2 md:grid-cols-3 gap-2">
                                        @foreach($images as $image)
                                            <div class="relative">
                                                <img src="{{ $image->temporaryUrl() }}" class="h-20 w-20 object-cover rounded border border-gray-200">
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Step 4: Review & Submit -->
                @if ($currentStep === 4)
                    <div class="bg-white rounded-xl shadow-sm p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Review & Submit Audit</h3>

                        <!-- Asset Summary -->
                        <div class="bg-gray-50 p-4 rounded-lg mb-6">
                            <h4 class="text-sm font-medium text-gray-900">Selected Asset</h4>
                            <div class="flex justify-between items-center mt-2">
                                <div>
                                    <p class="text-base font-medium text-gray-900">{{ $selectedAsset->name }}</p>
                                    <p class="text-sm text-gray-500">{{ $selectedAsset->asset_code }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm text-gray-500">Department: {{ $selectedAsset->department->name ?? 'N/A' }}</p>
                                    <p class="text-sm text-gray-500">Category: {{ $selectedAsset->category->name }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Audit Summary -->
                        <div class="space-y-4 bg-gray-50 p-4 rounded-lg mb-6">
                            <h4 class="text-sm font-medium text-gray-900">Audit Summary</h4>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <p class="text-sm font-medium text-gray-900">Location Verified</p>
                                    <p class="text-sm text-gray-500">{{ $locationVerified ? 'Yes' : 'No' }}</p>
                                    @if(!$locationVerified)
                                        <p class="text-sm text-gray-500 mt-1">Actual Location: {{ $actualLocation }}</p>
                                    @endif
                                </div>

                                <div>
                                    <p class="text-sm font-medium text-gray-900">Condition</p>
                                    <div class="flex items-center">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                            @if($previousCondition === 'new') bg-green-100 text-green-800
                                            @elseif($previousCondition === 'good') bg-blue-100 text-blue-800
                                            @elseif($previousCondition === 'fair') bg-yellow-100 text-yellow-800
                                            @else bg-red-100 text-red-800
                                            @endif mr-2">
                                            {{ ucfirst($previousCondition) }}
                                        </span>
                                        <svg class="h-4 w-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                        </svg>
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                            @if($newCondition === 'new') bg-green-100 text-green-800
                                            @elseif($newCondition === 'good') bg-blue-100 text-blue-800
                                            @elseif($newCondition === 'fair') bg-yellow-100 text-yellow-800
                                            @else bg-red-100 text-red-800
                                            @endif ml-2">
                                            {{ ucfirst($newCondition) }}
                                        </span>
                                    </div>
                                </div>

                                @if($discrepancies)
                                    <div class="col-span-2">
                                        <p class="text-sm font-medium text-gray-900">Discrepancies</p>
                                        <p class="text-sm text-gray-500">{{ $discrepancies }}</p>
                                    </div>
                                @endif

                                @if($locationNotes)
                                    <div class="col-span-2">
                                        <p class="text-sm font-medium text-gray-900">Location Notes</p>
                                        <p class="text-sm text-gray-500">{{ $locationNotes }}</p>
                                    </div>
                                @endif

                                @if (!empty($images))
                                    <div class="col-span-2">
                                        <p class="text-sm font-medium text-gray-900">Images</p>
                                        <div class="mt-2 grid grid-cols-3 md:grid-cols-4 gap-2">
                                            @foreach($images as $image)
                                                <div class="relative">
                                                    <img src="{{ $image->temporaryUrl() }}" class="h-16 w-16 object-cover rounded border border-gray-200">
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Final Details -->
                        <div class="space-y-4">
                            <div>
                                <label for="actionTaken" class="block text-sm font-medium text-gray-700">Action Taken</label>
                                <input type="text" wire:model="actionTaken" id="actionTaken"
                                       class="mt-1 focus:ring-emerald-500 focus:border-emerald-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                       placeholder="Describe any actions taken during this audit (if any)">
                                @error('actionTaken') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label for="additionalNotes" class="block text-sm font-medium text-gray-700">Additional Notes</label>
                                <textarea wire:model="additionalNotes" id="additionalNotes" rows="3"
                                          class="mt-1 focus:ring-emerald-500 focus:border-emerald-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                          placeholder="Any additional notes about this audit (optional)"></textarea>
                                @error('additionalNotes') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Navigation Buttons -->
                <div class="flex justify-between pt-4">
                    <button
                        wire:click="previousStep"
                        class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 {{ $currentStep === 1 ? 'invisible' : '' }}"
                    >
                        <svg class="-ml-1 mr-2 h-5 w-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                        Previous
                    </button>

                    @if($currentStep < $totalSteps)
                        <button
                            wire:click="nextStep"
                            class="inline-flex items-center px-4 py-2 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-emerald-600 hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500"
                        >
                            Next
                            <svg class="-mr-1 ml-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </button>
                    @else
                        <button
                            wire:click="createAudit"
                            class="inline-flex items-center px-4 py-2 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-emerald-600 hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500"
                        >
                            Submit Audit
                            <svg class="-mr-1 ml-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                        </button>
                    @endif
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Selected Asset Details -->
                @if($selectedAsset)
                    <div class="bg-white shadow-sm rounded-lg p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Asset Details</h3>
                        <div class="space-y-4">
                            <div>
                                <p class="text-sm font-medium text-gray-900">Asset Code</p>
                                <p class="text-sm text-gray-500">{{ $selectedAsset->asset_code }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">Serial Number</p>
                                <p class="text-sm text-gray-500">{{ $selectedAsset->serial_number ?: 'N/A' }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">Manufacturer</p>
                                <p class="text-sm text-gray-500">{{ $selectedAsset->manufacturer ?: 'N/A' }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">Model</p>
                                <p class="text-sm text-gray-500">{{ $selectedAsset->model ?: 'N/A' }}</p>
                            </div>
                            @if($selectedAsset->assignedUser)
                                <div>
                                    <p class="text-sm font-medium text-gray-900">Assigned To</p>
                                    <p class="text-sm text-gray-500">{{ $selectedAsset->assignedUser->name }}</p>
                                </div>
                            @endif
                            @if($selectedAsset->purchase_date)
                                <div>
                                    <p class="text-sm font-medium text-gray-900">Purchase Date</p>
                                    <p class="text-sm text-gray-500">{{ $selectedAsset->purchase_date->format('M d, Y') }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif

                <!-- Recent Audits -->
                <div class="bg-white shadow-sm rounded-lg p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Recent Audits</h3>
                    <div class="space-y-4">
                        @forelse($recentAudits as $audit)
                            <div class="border-b border-gray-200 pb-4 last:border-0 last:pb-0">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">{{ $audit->asset->name }}</p>
                                        <p class="text-xs text-gray-500">{{ $audit->asset->asset_code }}</p>
                                    </div>
                                    <span class="text-xs text-gray-500">
                                        {{ $audit->audit_date->format('M d, Y') }}
                                    </span>
                                </div>
                                <div class="mt-1 flex justify-between items-center">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                        @if($audit->new_condition === 'new') bg-green-100 text-green-800
                                        @elseif($audit->new_condition === 'good') bg-blue-100 text-blue-800
                                        @elseif($audit->new_condition === 'fair') bg-yellow-100 text-yellow-800
                                        @else bg-red-100 text-red-800
                                        @endif">
                                        {{ ucfirst($audit->new_condition) }}
                                    </span>
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                        @if($audit->location_verified) bg-green-100 text-green-800 @else bg-red-100 text-red-800 @endif">
                                        {{ $audit->location_verified ? 'Location verified' : 'Location changed' }}
                                    </span>
                                </div>
                            </div>
                        @empty
                            <div class="text-center text-gray-500 py-4">
                                <p class="text-sm">No recent audits</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Notification -->
    <div x-data="{ show: false, message: '' }"
         @audit-created.window="show = true; message = 'Audit submitted successfully!'"
         x-show="show"
         x-init="setTimeout(() => show = false, 3000)"
         class="fixed bottom-4 right-4 z-50">
        <div class="bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg">
            <span x-text="message"></span>
        </div>
    </div>
</div>

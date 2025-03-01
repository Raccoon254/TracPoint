<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Header with Actions -->
        <div class="md:flex md:items-center md:justify-between mb-6">
            <div class="min-w-0 flex-1">
                <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:truncate sm:tracking-tight">
                    Audit Details
                </h2>
                <div class="mt-1 flex flex-col sm:flex-row sm:flex-wrap sm:space-x-6">
                    <div class="mt-2 flex items-center text-sm text-gray-500">
                        <svg class="mr-1.5 h-5 w-5 flex-shrink-0 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        {{ $audit->audit_date->format('M d, Y') }}
                    </div>
                    <div class="mt-2 flex items-center text-sm text-gray-500">
                        <svg class="mr-1.5 h-5 w-5 flex-shrink-0 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        Audited by {{ $audit->auditor->name }}
                    </div>
                </div>
            </div>
            <div class="mt-4 flex md:mt-0 md:ml-4 space-x-2">
                @if($canEditAudit)
                    <a href="{{ route('audits.edit', $audit) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                        <svg class="-ml-1 mr-2 h-5 w-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        Edit
                    </a>
                @endif
                <button wire:click="downloadReport" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                    <svg class="-ml-1 mr-2 h-5 w-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                    </svg>
                    Download Report
                </button>
                <a href="{{ route('audits.index') }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-emerald-600 hover:bg-emerald-700">
                    <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Back to Audits
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main content -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Asset Information Card -->
                <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                    <div class="border-b border-gray-200 px-6 py-4 bg-gray-50">
                        <h3 class="text-lg font-medium text-gray-900">Asset Information</h3>
                    </div>
                    <div class="px-6 py-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm font-medium text-gray-500">Asset Name</p>
                                <p class="mt-1 text-sm text-gray-900">{{ $audit->asset->name }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500">Asset Code</p>
                                <p class="mt-1 text-sm text-gray-900">{{ $audit->asset->asset_code }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500">Category</p>
                                <p class="mt-1 text-sm text-gray-900">{{ $audit->asset->category->name }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500">Department</p>
                                <p class="mt-1 text-sm text-gray-900">{{ $audit->asset->department->name ?? 'Not assigned to a department' }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500">Current Status</p>
                                <p class="mt-1 text-sm text-gray-900">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                        @if($audit->asset->status === 'available') bg-green-100 text-green-800
                                        @elseif($audit->asset->status === 'assigned') bg-blue-100 text-blue-800
                                        @elseif($audit->asset->status === 'in_maintenance') bg-yellow-100 text-yellow-800
                                        @else bg-gray-100 text-gray-800
                                        @endif">
                                        {{ ucfirst($audit->asset->status) }}
                                    </span>
                                </p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500">Assigned To</p>
                                <p class="mt-1 text-sm text-gray-900">{{ $audit->asset->assignedUser->name ?? 'Not assigned' }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Audit Details Card -->
                <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                    <div class="border-b border-gray-200 px-6 py-4 bg-gray-50">
                        <h3 class="text-lg font-medium text-gray-900">Audit Details</h3>
                    </div>
                    <div class="px-6 py-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm font-medium text-gray-500">Condition Before</p>
                                <p class="mt-1">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                        @if($audit->previous_condition === 'new') bg-green-100 text-green-800
                                        @elseif($audit->previous_condition === 'good') bg-blue-100 text-blue-800
                                        @elseif($audit->previous_condition === 'fair') bg-yellow-100 text-yellow-800
                                        @else bg-red-100 text-red-800
                                        @endif">
                                        {{ ucfirst($audit->previous_condition) }}
                                    </span>
                                </p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500">Condition After</p>
                                <p class="mt-1">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                        @if($audit->new_condition === 'new') bg-green-100 text-green-800
                                        @elseif($audit->new_condition === 'good') bg-blue-100 text-blue-800
                                        @elseif($audit->new_condition === 'fair') bg-yellow-100 text-yellow-800
                                        @else bg-red-100 text-red-800
                                        @endif">
                                        {{ ucfirst($audit->new_condition) }}
                                    </span>
                                </p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500">Location Verified</p>
                                <p class="mt-1">
                                    @if($audit->location_verified)
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                            Yes
                                        </span>
                                    @else
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                            No
                                        </span>
                                    @endif
                                </p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500">Discrepancies Found</p>
                                <p class="mt-1">
                                    @if($audit->discrepancies)
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                            Yes
                                        </span>
                                    @else
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                            No
                                        </span>
                                    @endif
                                </p>
                            </div>
                            <div class="md:col-span-2">
                                <p class="text-sm font-medium text-gray-500">Action Taken</p>
                                <p class="mt-1 text-sm text-gray-900">{{ $audit->action_taken ?: 'No action recorded' }}</p>
                            </div>
                        </div>

                        @if($audit->discrepancies)
                            <div class="mt-4">
                                <p class="text-sm font-medium text-gray-500">Discrepancy Details</p>
                                <div class="mt-1 text-sm text-gray-900 p-3 bg-red-50 rounded-md">
                                    {{ $audit->discrepancies }}
                                </div>
                            </div>
                        @endif

                        @if($audit->notes)
                            <div class="mt-4">
                                <p class="text-sm font-medium text-gray-500">Notes</p>
                                <div class="mt-1 text-sm text-gray-900 p-3 bg-gray-50 rounded-md">
                                    {{ $audit->notes }}
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Audit Images -->
                @if($this->hasImages)
                    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                        <div class="border-b border-gray-200 px-6 py-4 bg-gray-50">
                            <h3 class="text-lg font-medium text-gray-900">Audit Images</h3>
                        </div>
                        <div class="px-6 py-4">
                            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                                @foreach($audit->images as $index => $imagePath)
                                    <div class="aspect-w-1 aspect-h-1 w-full overflow-hidden rounded-lg bg-gray-100">
                                        <img src="{{ $this->getImageUrl($imagePath) }}"
                                             alt="Audit image {{ $index + 1 }}"
                                             class="h-full w-full object-cover object-center cursor-pointer hover:opacity-75"
                                             wire:click="openGallery({{ $index }})">
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Schedule Next Audit Button -->
                <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                    <div class="px-6 py-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-900">Next Scheduled Audit</p>
                                <p class="text-sm text-gray-500">{{ $this->nextScheduledAuditDate->format('M d, Y') }}</p>
                            </div>
                            <button wire:click="scheduleFollowUpAudit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-emerald-600 hover:bg-emerald-700">
                                <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                Schedule Follow-up
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Audit History Card -->
                <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                    <div class="border-b border-gray-200 px-6 py-4 bg-gray-50 flex justify-between items-center">
                        <h3 class="text-lg font-medium text-gray-900">Audit History</h3>
                        <button wire:click="toggleHistoryModal" class="text-sm text-emerald-600 hover:text-emerald-700">
                            View All
                        </button>
                    </div>
                    <div class="px-6 py-4">
                        <div class="space-y-4">
                            @forelse($this->assetAuditHistory->take(3) as $historyItem)
                                <div class="border-b border-gray-200 pb-4 last:border-0 last:pb-0">
                                    <div class="flex justify-between">
                                        <p class="text-sm text-gray-500">{{ $historyItem->audit_date->format('M d, Y') }}</p>
                                        <p class="text-sm text-gray-500">{{ $historyItem->auditor->name }}</p>
                                    </div>
                                    <div class="mt-1 flex justify-between">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                            @if($historyItem->new_condition === 'new') bg-green-100 text-green-800
                                            @elseif($historyItem->new_condition === 'good') bg-blue-100 text-blue-800
                                            @elseif($historyItem->new_condition === 'fair') bg-yellow-100 text-yellow-800
                                            @else bg-red-100 text-red-800
                                            @endif">
                                            {{ ucfirst($historyItem->new_condition) }}
                                        </span>
                                        <a href="{{ route('audits.show', $historyItem) }}" class="text-sm text-emerald-600 hover:text-emerald-700">
                                            View
                                        </a>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-4">
                                    <p class="text-sm text-gray-500">No previous audit history</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- Related Assets Card (if applicable) -->
                <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                    <div class="border-b border-gray-200 px-6 py-4 bg-gray-50">
                        <h3 class="text-lg font-medium text-gray-900">Related Information</h3>
                    </div>
                    <div class="px-6 py-4">
                        <div class="space-y-4">
                            <div>
                                <p class="text-sm font-medium text-gray-500">Department</p>
                                @if($audit->asset->department)
                                    <a href="#" class="mt-1 text-sm text-emerald-600 hover:text-emerald-700 flex items-center">
                                        {{ $audit->asset->department->name }}
                                        <svg class="ml-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                        </svg>
                                    </a>
                                @else
                                    <p class="mt-1 text-sm text-gray-500">Not assigned to a department</p>
                                @endif
                            </div>

                            <div>
                                <p class="text-sm font-medium text-gray-500">Asset Details</p>
                                <a href="{{ route('assets.show', $audit->asset) }}" class="mt-1 text-sm text-emerald-600 hover:text-emerald-700 flex items-center">
                                    View Asset Details
                                    <svg class="ml-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                    </svg>
                                </a>
                            </div>

                            @if($audit->asset->assignedUser)
                                <div>
                                    <p class="text-sm font-medium text-gray-500">Assigned User</p>
                                    <a href="#" class="mt-1 text-sm text-emerald-600 hover:text-emerald-700 flex items-center">
                                        {{ $audit->asset->assignedUser->name }}
                                        <svg class="ml-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                        </svg>
                                    </a>
                                </div>
                            @endif

                            @if($audit->discrepancies)
                                <div>
                                    <p class="text-sm font-medium text-gray-500">Maintenance Required</p>
                                    <a href="#" class="mt-1 text-sm text-red-600 hover:text-red-700 flex items-center">
                                        Schedule Maintenance
                                        <svg class="ml-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                        </svg>
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Image Gallery Modal -->
    @if($isGalleryOpen && $this->hasImages)
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="image-gallery" role="dialog" aria-modal="true">
            <div class="flex min-h-screen items-center justify-center p-4 text-center">
                <!-- Background overlay -->
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>

                <!-- Gallery content -->
                <div class="relative transform overflow-hidden rounded-lg bg-white shadow-xl transition-all max-w-4xl w-full">
                    <div class="absolute top-0 right-0 pt-4 pr-4">
                        <button wire:click="closeGallery" type="button" class="rounded-md bg-white text-gray-400 hover:text-gray-500 focus:outline-none">
                            <span class="sr-only">Close</span>
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <div class="px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                        <div class="flex justify-center items-center">
                            <button wire:click="previousImage" class="p-2 rounded-full bg-gray-200 hover:bg-gray-300 mr-4">
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                                </svg>
                            </button>

                            <div class="relative h-96 w-full">
                                <img src="{{ $this->getImageUrl($audit->images[$currentImageIndex]) }}"
                                     alt="Audit image {{ $currentImageIndex + 1 }}"
                                     class="h-full w-full object-contain">
                                <p class="absolute bottom-2 right-2 bg-black bg-opacity-50 text-white px-2 py-1 rounded text-sm">
                                    {{ $currentImageIndex + 1 }} / {{ count($audit->images) }}
                                </p>
                            </div>

                            <button wire:click="nextImage" class="p-2 rounded-full bg-gray-200 hover:bg-gray-300 ml-4">
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Audit History Modal -->
    @if($showHistoryModal)
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="audit-history" role="dialog" aria-modal="true">
            <div class="flex min-h-screen items-center justify-center p-4 text-center">
                <!-- Background overlay -->
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>

                <!-- Modal content -->
                <div class="relative transform overflow-hidden rounded-lg bg-white shadow-xl transition-all max-w-2xl w-full">
                    <div class="absolute top-0 right-0 pt-4 pr-4">
                        <button wire:click="toggleHistoryModal" type="button" class="rounded-md bg-white text-gray-400 hover:text-gray-500 focus:outline-none">
                            <span class="sr-only">Close</span>
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <div class="px-4 pb-4 pt-5 sm:p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Audit History for {{ $audit->asset->name }}</h3>

                        <div class="overflow-y-auto max-h-96">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Auditor</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Condition</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Location</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($this->assetAuditHistory as $historyItem)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $historyItem->audit_date->format('M d, Y') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $historyItem->auditor->name }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                                    @if($historyItem->new_condition === 'new') bg-green-100 text-green-800
                                                    @elseif($historyItem->new_condition === 'good') bg-blue-100 text-blue-800
                                                    @elseif($historyItem->new_condition === 'fair') bg-yellow-100 text-yellow-800
                                                    @else bg-red-100 text-red-800
                                                    @endif">
                                                    {{ ucfirst($historyItem->new_condition) }}
                                                </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            @if($historyItem->location_verified)
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                        Verified
                                                    </span>
                                            @else
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                        Changed
                                                    </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <a href="{{ route('audits.show', $historyItem) }}" class="text-emerald-600 hover:text-emerald-900">View</a>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>

                            @if($this->assetAuditHistory->isEmpty())
                                <div class="text-center py-8">
                                    <p class="text-sm text-gray-500">No previous audit history</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Notification Toasts -->
    <div x-data="{ showReportNotification: false, showFollowUpNotification: false }"
         @report-download-initiated.window="showReportNotification = true; setTimeout(() => showReportNotification = false, 3000)"
         @follow-up-scheduled.window="showFollowUpNotification = true; setTimeout(() => showFollowUpNotification = false, 3000)"
         class="fixed bottom-4 right-4 z-50 space-y-2">

        <div x-show="showReportNotification" x-transition class="bg-emerald-500 text-white px-6 py-3 rounded-lg shadow-lg">
            <span>Report download initiated. Feature coming soon!</span>
        </div>

        <div x-show="showFollowUpNotification" x-transition class="bg-emerald-500 text-white px-6 py-3 rounded-lg shadow-lg">
            <span>Follow-up audit scheduled. Feature coming soon!</span>
        </div>
    </div>
</div>

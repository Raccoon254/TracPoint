<div class="py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="md:flex md:items-center md:justify-between mb-6">
            <div class="min-w-0 flex-1">
                <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:truncate sm:text-3xl sm:tracking-tight">
                    {{ $asset->name }}
                </h2>
                <div class="mt-1 flex flex-col sm:mt-0 sm:flex-row sm:flex-wrap sm:space-x-6">
                    <div class="mt-2 flex items-center text-sm text-gray-500">
                        Asset Code: {{ $asset->asset_code }}
                    </div>
                    <div class="mt-2 flex items-center text-sm text-gray-500">
                        Category: {{ $asset->category->name }}
                    </div>
                </div>
            </div>
            <div class="mt-4 flex md:ml-4 md:mt-0">
                <a href="{{ route('assets.edit', $asset) }}"
                   class="ml-3 inline-flex items-center rounded-md bg-emerald-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-emerald-700">
                    Edit Asset
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
            <!-- Main Information -->
            <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg font-medium leading-6 text-gray-900 mb-4">Asset Information</h3>
                    <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Model</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $asset->model ?: 'N/A' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Manufacturer</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $asset->manufacturer ?: 'N/A' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Serial Number</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $asset->serial_number ?: 'N/A' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Barcode</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $asset->barcode ?: 'N/A' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Status</dt>
                            <dd class="mt-1">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                    @if($asset->status === 'available') bg-green-100 text-green-800
                                    @elseif($asset->status === 'assigned') bg-blue-100 text-blue-800
                                    @elseif($asset->status === 'in_maintenance') bg-yellow-100 text-yellow-800
                                    @else bg-gray-100 text-gray-800
                                    @endif">
                                    {{ ucfirst($asset->status) }}
                                </span>
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Condition</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ ucfirst($asset->condition) }}</dd>
                        </div>
                    </dl>
                </div>
            </div>

            <!-- Financial Information -->
            <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg font-medium leading-6 text-gray-900 mb-4">Financial Details</h3>
                    <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Value</dt>
                            <dd class="mt-1 text-sm text-gray-900">${{ number_format($asset->value, 2) }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Purchase Date</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $asset->purchase_date?->format('M d, Y') ?: 'N/A' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Warranty Expiry</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $asset->warranty_expiry?->format('M d, Y') ?: 'N/A' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Depreciation Rate</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $asset->depreciation_rate }}%</dd>
                        </div>
                    </dl>
                </div>
            </div>

            <!-- Location & Assignment -->
            <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg font-medium leading-6 text-gray-900 mb-4">Location & Assignment</h3>
                    <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Department</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $asset->department->name ?? 'N/A' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Location</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $asset->current_location ?: 'N/A' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Assigned To</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $asset->assignedUser->name ?? 'Not Assigned' }}</dd>
                        </div>
                        @if($asset->assigned_to)
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Assignment Date</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $asset->assigned_date?->format('M d, Y') }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Expected Return</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $asset->expected_return_date?->format('M d, Y') ?: 'N/A' }}</dd>
                            </div>
                        @endif
                    </dl>
                </div>
            </div>

            <!-- Images -->
            <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg font-medium leading-6 text-gray-900 mb-4">Images</h3>
                    @if($asset->assetImages->isNotEmpty())
                        <div class="grid grid-cols-2 gap-4">
                            @foreach($asset->assetImages as $image)
                                <div class="relative">
                                    @if($image->image_type === 'primary')
                                        <span class="absolute top-2 left-2 px-2 py-1 text-xs font-semibold bg-emerald-100 text-emerald-800 rounded-md">
                                            Primary
                                        </span>
                                    @endif
                                    <img src="{{ Storage::url($image->image_path) }}"
                                         alt="Asset image"
                                         class="rounded-lg object-cover w-full h-48">
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div>
                            @include('partials.empty', [
                                'title' => 'No Images Available',
                                'message' => 'There are no images available for this asset.',
                                'icon' => 'image'
                            ])
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="mt-6">
            <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg font-medium leading-6 text-gray-900 mb-4">Recent Activity</h3>

                    <!-- Maintenance Records -->
                    @if($asset->maintenanceRecords->isNotEmpty())
                        <div class="mb-6">
                            <h4 class="text-sm font-medium text-gray-900 mb-2">Maintenance History</h4>
                            <ul class="space-y-3">
                                @foreach($asset->maintenanceRecords as $record)
                                    <li class="text-sm text-gray-600">
                                        {{ $record->created_at->format('M d, Y') }} - {{ $record->description }}
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <!-- Audit History -->
                    @if($asset->audits->isNotEmpty())
                        <div>
                            <h4 class="text-sm font-medium text-gray-900 mb-2">Audit History</h4>
                            <ul class="space-y-3">
                                @foreach($asset->audits as $audit)
                                    <li class="text-sm text-gray-600">
                                        {{ $audit->created_at->format('M d, Y') }} - {{ $audit->notes }}
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @if($asset->maintenanceRecords->isEmpty() && $asset->audits->isEmpty())
                        <p class="text-sm text-gray-500">No recent activity</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

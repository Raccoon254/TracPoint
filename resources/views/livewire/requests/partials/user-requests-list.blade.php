<div class="bg-white rounded-lg shadow-sm overflow-hidden border border-gray-100">
    <div class="px-4 py-5 border-b border-gray-200 sm:px-6">
        <h3 class="text-lg font-medium text-gray-900 flex items-center">
            <i data-lucide="file-text" class="w-5 h-5 mr-2 text-gray-500"></i>
            My Requests
        </h3>
    </div>

    <div class="flow-root">
        <ul role="list" class="divide-y divide-gray-200">
            @forelse($requests as $request)
                <li class="p-6 hover:bg-gray-50 transition duration-150">
                    <div class="flex items-center space-x-4">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 rounded-lg bg-emerald-100 flex items-center justify-center">
                                <img src="{{  Storage::url($request->asset->assetImages->first()->image_path) }}"
                                     alt="{{ $request->asset->name }}"
                                     class="w-10 h-10 object-cover rounded-lg">
                            </div>
                        </div>

                        <div class="flex-1 min-w-0">
                            <!-- Header Section -->
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-semibold text-gray-900">{{ $request->category->name }}</p>
                                    <p class="text-sm text-gray-500">Quantity: {{ $request->quantity }}</p>
                                </div>
                                <div class="flex items-center space-x-3">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium
                                        @if($request->status === 'pending') bg-yellow-100 text-yellow-800
                                        @elseif($request->status === 'approved') bg-blue-100 text-blue-800
                                        @elseif($request->status === 'fulfilled') bg-green-100 text-green-800
                                        @else bg-red-100 text-red-800
                                        @endif">
                                        <i data-lucide="{{
                                            $request->status === 'pending' ? 'clock' :
                                            ($request->status === 'approved' ? 'check-circle' :
                                            ($request->status === 'fulfilled' ? 'check-square' : 'x-circle'))
                                        }}" class="w-3 h-3 mr-1"></i>
                                        {{ ucfirst($request->status) }}
                                    </span>
                                </div>
                            </div>

                            <!-- Details Section -->
                            <div class="mt-4 bg-gray-50 rounded-lg p-4">
                                <p class="text-sm text-gray-600">
                                    <span class="font-medium">Purpose:</span> {{ $request->purpose }}
                                </p>
                                @if($request->notes)
                                    <p class="mt-2 text-sm text-gray-500">
                                        <span class="font-medium">Notes:</span> {{ $request->notes }}
                                    </p>
                                @endif
                            </div>

                            <!-- Timeline Section -->
                            <div class="mt-4 flex items-center space-x-6 text-sm text-gray-500">
                                <div class="flex items-center">
                                    <i data-lucide="calendar" class="w-4 h-4 mr-1.5 text-gray-400"></i>
                                    <span>Required: {{ $request->required_from->format('M d, Y') }} - {{ $request->required_until->format('M d, Y') }}</span>
                                </div>
                                @if($request->approver)
                                    <div class="flex items-center">
                                        <i data-lucide="user-check" class="w-4 h-4 mr-1.5 text-gray-400"></i>
                                        <span>{{ $request->approver->name }} ({{ $request->approval_date->format('M d, Y') }})</span>
                                    </div>
                                @endif
                            </div>

                            <!-- Assigned Asset (if fulfilled) -->
                            @if($request->status === 'fulfilled' && $request->asset)
                                <div class="mt-4 p-4 border border-gray-200 rounded-lg bg-blue-50">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center space-x-3">
                                            <i data-lucide="box" class="w-5 h-5 text-blue-600"></i>
                                            <div>
                                                <p class="text-sm font-medium text-gray-900">{{ $request->asset->name }}</p>
                                                <p class="text-xs text-gray-500">Asset Code: {{ $request->asset->asset_code }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </li>
            @empty
                <li class="p-6">
                    <div class="text-center">
                        <div class="mx-auto w-12 h-12 rounded-full bg-gray-100 flex items-center justify-center">
                            <i data-lucide="inbox" class="w-6 h-6 text-gray-400"></i>
                        </div>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">No Requests Found</h3>
                        <p class="mt-1 text-sm text-gray-500">Start by requesting an asset.</p>
                    </div>
                </li>
            @endforelse
        </ul>
    </div>

    @if($requests->hasPages())
        <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
            {{ $requests->links() }}
        </div>
    @endif
</div>

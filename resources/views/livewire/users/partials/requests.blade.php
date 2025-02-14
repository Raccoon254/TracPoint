<div class="space-y-6">
    <!-- Asset Requests List -->
    <div class="bg-white shadow-sm rounded-lg overflow-hidden border border-gray-100">
        <div class="px-4 py-5 border-b border-gray-200 sm:px-6">
            <h3 class="text-lg font-medium text-gray-900 flex items-center">
                <i data-lucide="clipboard-list" class="w-5 h-5 mr-2 text-gray-500"></i>
                Asset Requests
            </h3>
        </div>

        <div class="flow-root">
            <ul role="list" class="divide-y divide-gray-200">
                @forelse($assetRequests as $request)
                    <li class="p-6 hover:bg-gray-50 transition duration-150">
                        <div class="flex items-center justify-between">
                            <div class="flex-1 min-w-0">
                                <!-- Header Section -->
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center space-x-3">
                                        <div class="flex-shrink-0">
                                            <div class="w-10 h-10 rounded-full bg-emerald-100 flex items-center justify-center">
                                                <i data-lucide="box" class="w-5 h-5 text-emerald-600"></i>
                                            </div>
                                        </div>
                                        <div>
                                            <p class="text-sm font-semibold text-gray-900">
                                                {{ $request->category->name }}
                                            </p>
                                            <p class="text-sm text-gray-500 flex items-center">
                                                <i data-lucide="hash" class="w-4 h-4 mr-1"></i>
                                                Quantity: {{ $request->quantity }}
                                            </p>
                                        </div>
                                    </div>
                                    <div class="flex items-center space-x-3">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium
                                            @if($request->status === 'pending') bg-yellow-100 text-yellow-800
                                            @elseif($request->status === 'approved') bg-green-100 text-green-800
                                            @elseif($request->status === 'rejected') bg-red-100 text-red-800
                                            @else bg-gray-100 text-gray-800
                                            @endif">
                                            <i data-lucide="{{
                                                $request->status === 'pending' ? 'clock' :
                                                ($request->status === 'approved' ? 'check-circle' :
                                                ($request->status === 'rejected' ? 'x-circle' : 'help-circle'))
                                            }}" class="w-3 h-3 mr-1"></i>
                                            {{ ucfirst($request->status) }}
                                        </span>
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium
                                            @if($request->priority === 'high') bg-red-100 text-red-800
                                            @elseif($request->priority === 'medium') bg-yellow-100 text-yellow-800
                                            @else bg-blue-100 text-blue-800
                                            @endif">
                                            <i data-lucide="flag" class="w-3 h-3 mr-1"></i>
                                            {{ ucfirst($request->priority) }} Priority
                                        </span>
                                    </div>
                                </div>

                                <!-- Purpose & Notes Section -->
                                <div class="mt-4 bg-gray-50 rounded-lg p-4">
                                    <div class="flex items-start space-x-2">
                                        <i data-lucide="file-text" class="w-4 h-4 text-gray-400 mt-0.5"></i>
                                        <div class="flex-1">
                                            <p class="text-sm text-gray-600">
                                                <span class="font-medium">Purpose:</span> {{ $request->purpose }}
                                            </p>
                                            @if($request->notes)
                                                <p class="mt-2 text-sm text-gray-500">
                                                    <span class="font-medium">Notes:</span> {{ $request->notes }}
                                                </p>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <!-- Timeline Section -->
                                <div class="mt-4 flex flex-wrap items-center gap-4 text-sm text-gray-500">
                                    <div class="flex items-center">
                                        <i data-lucide="calendar" class="w-4 h-4 mr-1.5 text-gray-400"></i>
                                        <span class="font-medium">Required:</span>
                                        <span class="ml-1">{{ $request->required_from->format('M d, Y') }}</span>
                                        <i data-lucide="arrow-right" class="w-4 h-4 mx-1"></i>
                                        <span>{{ $request->required_until->format('M d, Y') }}</span>
                                    </div>
                                    @if($request->approver)
                                        <div class="flex items-center">
                                            <i data-lucide="user-check" class="w-4 h-4 mr-1.5 text-gray-400"></i>
                                            <span class="font-medium">Approved by:</span>
                                            <span class="ml-1 text-gray-900">{{ $request->approver->name }}</span>
                                            <span class="ml-1 text-gray-500">on</span>
                                            <span class="ml-1 text-gray-900">{{ $request->approval_date->format('M d, Y') }}</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </li>
                @empty
                    <li class="p-6">
                        @include('partials.empty', [
                            'title' => 'No Asset Requests',
                            'message' => 'Asset requests will appear here once submitted.',
                            'icon' => 'user'
                        ])
                    </li>
                @endforelse
            </ul>
        </div>

        @if($assetRequests->hasPages())
            <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                {{ $assetRequests->links() }}
            </div>
        @endif
    </div>
</div>

<div class="space-y-6">
    <!-- Asset Requests List -->
    <div class="bg-white shadow-sm rounded-lg overflow-hidden">
        <div class="flow-root">
            <ul role="list" class="divide-y divide-gray-200">
                @forelse($assetRequests as $request)
                    <li class="p-4 hover:bg-gray-50">
                        <div class="flex items-center justify-between">
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">
                                            {{ $request->category->name }}
                                        </p>
                                        <p class="text-sm text-gray-500">
                                            Quantity: {{ $request->quantity }}
                                        </p>
                                    </div>
                                    <div class="flex items-center space-x-4">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                            @if($request->status === 'pending') bg-yellow-100 text-yellow-800
                                            @elseif($request->status === 'approved') bg-green-100 text-green-800
                                            @elseif($request->status === 'rejected') bg-red-100 text-red-800
                                            @else bg-gray-100 text-gray-800
                                            @endif">
                                            {{ ucfirst($request->status) }}
                                        </span>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                            @if($request->priority === 'high') bg-red-100 text-red-800
                                            @elseif($request->priority === 'medium') bg-yellow-100 text-yellow-800
                                            @else bg-blue-100 text-blue-800
                                            @endif">
                                            {{ ucfirst($request->priority) }} Priority
                                        </span>
                                    </div>
                                </div>
                                <div class="mt-2">
                                    <p class="text-sm text-gray-600">
                                        Purpose: {{ $request->purpose }}
                                    </p>
                                    @if($request->notes)
                                        <p class="mt-1 text-sm text-gray-500">
                                            Notes: {{ $request->notes }}
                                        </p>
                                    @endif
                                </div>
                                <div class="mt-2 flex items-center space-x-4 text-sm text-gray-500">
                                    <div>
                                        <span class="font-medium">Required From:</span>
                                        <span class="ml-1">{{ $request->required_from->format('M d, Y') }}</span>
                                    </div>
                                    <div>
                                        <span class="font-medium">Until:</span>
                                        <span class="ml-1">{{ $request->required_until->format('M d, Y') }}</span>
                                    </div>
                                </div>
                                @if($request->approver)
                                    <div class="mt-2 text-sm">
                                        <span class="text-gray-500">Approved by:</span>
                                        <span class="text-gray-900">{{ $request->approver->name }}</span>
                                        <span class="text-gray-500">on</span>
                                        <span class="text-gray-900">{{ $request->approval_date->format('M d, Y') }}</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </li>
                @empty
                    <li class="p-4">
                        @include('partials.empty', [
                            'title' => 'No Requests Found',
                            'message' => 'Requests will appear here once they are submitted.',
                            'icon' => 'asset'
                        ])
                    </li>
                @endforelse
            </ul>
        </div>
        <div class="px-4 py-3 border-t border-gray-200">
            {{ $assetRequests->links() }}
        </div>
    </div>
</div>

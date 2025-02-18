<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="px-4 py-6 sm:px-0">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-semibold text-gray-900">Notifications</h2>

            @if($this->notifications->hasPages())
                <div class="px-4 py-3 bg-white border-t border-gray-200 sm:px-6">
                    {{ $this->notifications->links() }}
                </div>
            @endif
        </div>

        <div class="bg-white shadow-sm rounded-lg divide-y divide-gray-200">
            @forelse($this->notifications as $notification)
                <div wire:key="notification-{{ $notification->id }}"
                     class="p-4 {{ $notification->read_at ? 'bg-white' : 'bg-gray-50' }}">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            @switch($notification->type)
                                @case('asset_returned')
                                    <span class="inline-flex p-2 bg-emerald-100 rounded-full">
                                        <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                        </svg>
                                    </span>
                                    @break
                                @default
                                    <span class="inline-flex p-2 bg-blue-100 rounded-full">
                                        <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    </span>
                            @endswitch
                        </div>

                        <div class="ml-3 flex-1">
                            <div class="flex justify-between items-start">
                                <p class="text-sm font-medium text-gray-900">
                                    {{ $notification->message }}
                                </p>
                                <p class="ml-4 text-xs text-gray-500">
                                    {{ $notification->created_at->diffForHumans() }}
                                </p>
                            </div>

                            @if($notification->link && !$notification->read_at)
                                <div class="mt-2">
                                    <button wire:click="markAsRead('{{ $notification->id }}')"
                                            class="text-sm text-emerald-600 hover:text-emerald-700">
                                        View details
                                    </button>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="py-12">
                    <div class="text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                        </svg>
                        <p class="mt-4 text-sm text-gray-900">No notifications yet</p>
                    </div>
                </div>
            @endforelse
        </div>
    </div>
</div>

<div class="relative">
    <!-- Notification Button -->
    <button @click="$wire.showDropdown = !$wire.showDropdown"
            class="p-2 text-gray-500 hover:text-gray-700 rounded-full hover:bg-gray-100 relative">
        @if($hasUnread)
            <span class="absolute top-0 right-0 block h-2 w-2 rounded-full bg-red-500"></span>
        @endif
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
        </svg>
    </button>

    <!-- Notifications Dropdown -->
    <div x-show="$wire.showDropdown"
         @click.away="$wire.showDropdown = false"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="transform opacity-0 scale-95"
         x-transition:enter-end="transform opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-75"
         x-transition:leave-start="transform opacity-100 scale-100"
         x-transition:leave-end="transform opacity-0 scale-95"
         class="absolute right-0 mt-2 w-80 bg-white rounded-md shadow-lg z-50">
        <div class="p-2 border-b border-gray-100">
            <div class="flex justify-between items-center">
                <h3 class="text-sm font-semibold text-gray-900">Notifications</h3>
                @if($hasUnread)
                    <button wire:click="markAllAsRead" class="text-xs text-emerald-600 hover:text-emerald-700">
                        Mark all as read
                    </button>
                @endif
            </div>
        </div>

        <div class="max-h-96 overflow-y-auto">
            @forelse($notifications as $notification)
                <div wire:key="notification-{{ $notification->id }}"
                     class="p-4 {{ $notification->read_at ? 'bg-white' : 'bg-gray-50' }} hover:bg-gray-50 border-b border-gray-100 last:border-0">
                    <button wire:click="markAsRead('{{ $notification->id }}')" class="w-full text-left">
                        <div class="flex items-start">
                            <!-- Icon based on notification type -->
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

                            <div class="ml-3 w-0 flex-1">
                                <p class="text-sm font-medium text-gray-900">
                                    {{ $notification->message }}
                                </p>
                                <p class="mt-1 text-xs text-gray-500">
                                    {{ $notification->created_at->diffForHumans() }}
                                </p>
                            </div>
                        </div>
                    </button>
                </div>
            @empty
                <div class="py-8">
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

        <div class="p-2 border-t border-gray-100">
            <a href="{{ route('notifications.index') }}"
               class="block w-full text-center text-sm text-emerald-600 hover:text-emerald-700">
                View all notifications
            </a>
        </div>
    </div>
</div>

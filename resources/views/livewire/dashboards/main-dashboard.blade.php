<div>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Welcome Section -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-2xl font-bold text-gray-900">
                                Welcome back, {{ $user->name }}!
                            </h3>
                            <p class="text-gray-600">{{ \Carbon\Carbon::now()->format('l, F j, Y') }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm text-gray-600">Role: <span class="font-semibold">{{ ucfirst($user->role) }}</span></p>
                            <p class="text-sm text-gray-600">Department: <span class="font-semibold">{{ $user->department->name ?? 'N/A' }}</span></p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Stats Grid -->
            <div class="">
                @if($user->role === 'super_admin')
                    <livewire:dashboards.super-admin-dashboard :stats="$stats" />
                @elseif($user->role === 'admin')
                    <livewire:dashboards.admin-dashboard />
                @elseif($user->role === 'auditor')
                    <livewire:dashboards.auditor-dashboard :stats="$stats" />
                @else
                    <livewire:dashboards.user-dashboard :stats="$stats" />
                @endif
            </div>

            <!-- Quick Actions Section -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Actions</h3>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        @if(in_array($user->role, ['super_admin', 'admin']))
                            <a href="{{ route('assets.create') }}" class="flex items-center p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                                <svg class="h-6 w-6 text-emerald-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                </svg>
                                Add New Asset
                            </a>
                            <a href="{{route('users.index')}}" class="flex items-center p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                                <svg class="h-6 w-6 text-emerald-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                </svg>
                                Manage Users
                            </a>
                        @endif

                        @if($user->role === 'auditor')
                            <a href="#" class="flex items-center p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                                <svg class="h-6 w-6 text-emerald-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                                </svg>
                                Start New Audit
                            </a>
                        @endif

                        <a href="#" class="flex items-center p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                            <svg class="h-6 w-6 text-emerald-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                            </svg>
                            Request Asset
                        </a>

                        <a href="#" class="flex items-center p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                            <svg class="h-6 w-6 text-emerald-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            View Schedule
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

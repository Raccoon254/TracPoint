<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- User Header Card -->
        <div class="bg-white rounded-lg shadow-sm overflow-hidden mb-6 border border-gray-100">
            <div class="p-6">
                <div class="flex items-start justify-between">
                    <!-- User Basic Info -->
                    <div class="flex items-center space-x-6">
                        <div class="h-20 w-20 rounded-full bg-gradient-to-br from-emerald-100 to-emerald-200 flex items-center justify-center shadow-inner">
                    <span class="text-2xl font-bold text-emerald-700">
                        {{ substr($user->name, 0, 2) }}
                    </span>
                        </div>
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900">{{ $user->name }}</h1>
                            <div class="mt-2 space-y-2">
                                <p class="text-sm text-gray-500 flex items-center space-x-2">
                                    <i data-lucide="mail" class="w-4 h-4"></i>
                                    <span>{{ $user->email }}</span>
                                </p>
                                <div class="flex items-center space-x-3">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium
                                @if($user->role === 'super_admin') bg-purple-100 text-purple-800
                                @elseif($user->role === 'admin') bg-blue-100 text-blue-800
                                @elseif($user->role === 'auditor') bg-yellow-100 text-yellow-800
                                @else bg-gray-100 text-gray-800
                                @endif">
                                <i data-lucide="shield" class="w-3 h-3 mr-1"></i>
                                {{ ucfirst($user->role) }}
                            </span>
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium
                                @if($user->status === 'active') bg-green-100 text-green-800
                                @else bg-red-100 text-red-800
                                @endif">
                                <i data-lucide="{{ $user->status === 'active' ? 'check-circle' : 'x-circle' }}" class="w-3 h-3 mr-1"></i>
                                {{ ucfirst($user->status) }}
                            </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="flex space-x-3">
                        <a href=""
                           class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500">
                            <i data-lucide="edit" class="w-4 h-4 mr-2"></i>
                            Edit Profile
                        </a>
                        @if($user->status === 'active')
                            <button type="button"
                                    class="inline-flex items-center px-4 py-2 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-emerald-600 hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500">
                                <i data-lucide="plus" class="w-4 h-4 mr-2"></i>
                                Assign Asset
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-6 gap-6 mb-6">
            <!-- Total Assets Value -->
            <div class="bg-white/10 cursor-pointer rounded-lg shadow-sm p-6 border border-gray-100 hover:shadow-md transition-shadow duration-200 relative overflow-hidden">
                <div class="absolute top-0 right-0 w-20 h-20 transform translate-x-6 -translate-y-6">
                    <div class="absolute inset-0 bg-emerald-100 rounded-full opacity-20"></div>
                </div>
                <dt class="text-sm font-medium text-gray-500 flex items-center space-x-2">
                    <i data-lucide="coins" class="w-4 h-4 text-emerald-500"></i>
                    <span>Assets Value</span>
                </dt>
                <dd class="mt-3 text-2xl font-bold text-gray-900">
                    ${{ number_format($statistics['total_assets_value'], 2) }}
                </dd>
                <div class="mt-1 text-xs text-gray-500">
                    Across {{ $statistics['active_assets'] }} assets
                </div>
            </div>

            <!-- Active Assets -->
            <div class="bg-white/10 cursor-pointer rounded-lg shadow-sm p-6 border border-gray-100 hover:shadow-md transition-shadow duration-200 relative overflow-hidden">
                <div class="absolute top-0 right-0 w-20 h-20 transform translate-x-6 -translate-y-6">
                    <div class="absolute inset-0 bg-blue-100 rounded-full opacity-20"></div>
                </div>
                <dt class="text-sm font-medium text-gray-500 flex items-center space-x-2">
                    <i data-lucide="box" class="w-4 h-4 text-blue-500"></i>
                    <span>Active Assets</span>
                </dt>
                <dd class="mt-3 text-2xl font-bold text-gray-900">
                    {{ $statistics['active_assets'] }}
                </dd>
                <div class="mt-1 text-xs text-gray-500">
                    Currently in use
                </div>
            </div>

            <!-- Asset History -->
            <div class="bg-white/10 cursor-pointer rounded-lg shadow-sm p-6 border border-gray-100 hover:shadow-md transition-shadow duration-200 relative overflow-hidden">
                <div class="absolute top-0 right-0 w-20 h-20 transform translate-x-6 -translate-y-6">
                    <div class="absolute inset-0 bg-purple-100 rounded-full opacity-20"></div>
                </div>
                <dt class="text-sm font-medium text-gray-500 flex items-center space-x-2">
                    <i data-lucide="history" class="w-4 h-4 text-purple-500"></i>
                    <span>Assets History</span>
                </dt>
                <dd class="mt-3 text-2xl font-bold text-gray-900">
                    {{ $statistics['assets_history'] }}
                </dd>
                <div class="mt-1 text-xs text-gray-500">
                    Last {{ $timeframe }} days
                </div>
            </div>

            <!-- Audits Conducted -->
            <div class="bg-white/10 cursor-pointer rounded-lg shadow-sm p-6 border border-gray-100 hover:shadow-md transition-shadow duration-200 relative overflow-hidden">
                <div class="absolute top-0 right-0 w-20 h-20 transform translate-x-6 -translate-y-6">
                    <div class="absolute inset-0 bg-yellow-100 rounded-full opacity-20"></div>
                </div>
                <dt class="text-sm font-medium text-gray-500 flex items-center space-x-2">
                    <i data-lucide="clipboard-check" class="w-4 h-4 text-yellow-500"></i>
                    <span>Audits</span>
                </dt>
                <dd class="mt-3 text-2xl font-bold text-gray-900">
                    {{ $statistics['audits_conducted'] }}
                </dd>
                <div class="mt-1 text-xs text-gray-500">
                    Completed audits ({{ $timeframe }}d)
                </div>
            </div>

            <!-- Pending Requests -->
            <div class="bg-white/10 cursor-pointer rounded-lg shadow-sm p-6 border border-gray-100 hover:shadow-md transition-shadow duration-200 relative overflow-hidden">
                <div class="absolute top-0 right-0 w-20 h-20 transform translate-x-6 -translate-y-6">
                    <div class="absolute inset-0 bg-indigo-100 rounded-full opacity-20"></div>
                </div>
                <dt class="text-sm font-medium text-gray-500 flex items-center space-x-2">
                    <i data-lucide="clock" class="w-4 h-4 text-indigo-500"></i>
                    <span>New Requests</span>
                </dt>
                <dd class="mt-3 text-2xl font-bold text-gray-900">
                    {{ $statistics['pending_requests'] }}
                </dd>
                <div class="mt-1 text-xs text-gray-500">
                    Awaiting approval
                </div>
            </div>

            <!-- Maintenance Items -->
            <div class="bg-white/10 cursor-pointer rounded-lg shadow-sm p-6 border border-gray-100 hover:shadow-md transition-shadow duration-200 relative overflow-hidden">
                <div class="absolute top-0 right-0 w-20 h-20 transform translate-x-6 -translate-y-6">
                    <div class="absolute inset-0 bg-red-100 rounded-full opacity-20"></div>
                </div>
                <dt class="text-sm font-medium text-gray-500 flex items-center space-x-2">
                    <i data-lucide="bolt" class="w-4 h-4 text-red-500"></i>
                    <span>Maintenance</span>
                </dt>
                <dd class="mt-3 text-2xl font-bold text-gray-900">
                    {{ $statistics['maintenance_items'] }}
                </dd>
                <div class="mt-1 text-xs text-gray-500">
                    Items requiring attention
                </div>
            </div>
        </div>

        <!-- Tabs -->
        <div class="bg-white rounded-lg shadow-sm mb-6">
            <div class="border-b border-gray-200">
                <nav class="-mb-px flex space-x-8 px-6" aria-label="Tabs">
                    @php
                        $tabs = [
                            'overview' => 'Overview',
                            'assets' => 'Assigned Assets',
                            'audits' => 'Audits History',
                            'requests' => 'Asset Requests',
                        ];
                    @endphp

                    @foreach($tabs as $key => $label)
                        <button wire:click="$set('activeTab', '{{ $key }}')"
                                class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm
                                    {{ $activeTab === $key
                                        ? 'border-emerald-500 text-emerald-600'
                                        : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'
                                    }}">
                            {{ $label }}
                        </button>
                    @endforeach
                </nav>
            </div>
        </div>

        <!-- Tab Content -->
        <div>
            @if($activeTab === 'overview')
                <!-- Overview Content -->
                <!-- Include overview partial -->
                @include('livewire.users.partials.overview')
            @elseif($activeTab === 'assets')
                <!-- Assets Content -->
                <!-- Include assets partial -->
                @include('livewire.users.partials.assets')
            @elseif($activeTab === 'audits')
                <!-- Audits Content -->
                <!-- Include audits partial -->
                @include('livewire.users.partials.audits')
            @else
                <!-- Requests Content -->
                <!-- Include requests partial -->
                @include('livewire.users.partials.requests')
            @endif
        </div>
    </div>
</div>

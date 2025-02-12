<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- User Header Card -->
        <div class="bg-white rounded-lg shadow-sm overflow-hidden mb-6">
            <div class="p-6">
                <div class="flex items-start justify-between">
                    <!-- User Basic Info -->
                    <div class="flex items-center space-x-4">
                        <div class="h-16 w-16 rounded-full bg-emerald-100 flex items-center justify-center">
                            <span class="text-2xl font-bold text-emerald-700">
                                {{ substr($user->name, 0, 2) }}
                            </span>
                        </div>
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900">{{ $user->name }}</h1>
                            <div class="mt-1 space-y-1">
                                <p class="text-sm text-gray-500">{{ $user->email }}</p>
                                <div class="flex items-center space-x-2">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        @if($user->role === 'super_admin') bg-purple-100 text-purple-800
                                        @elseif($user->role === 'admin') bg-blue-100 text-blue-800
                                        @elseif($user->role === 'auditor') bg-yellow-100 text-yellow-800
                                        @else bg-gray-100 text-gray-800
                                        @endif">
                                        {{ ucfirst($user->role) }}
                                    </span>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        @if($user->status === 'active') bg-green-100 text-green-800
                                        @else bg-red-100 text-red-800
                                        @endif">
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
                            Edit Profile
                        </a>
                        @if($user->status === 'active')
                            <button type="button"
                                    class="inline-flex items-center px-4 py-2 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-emerald-600 hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500">
                                Assign Asset
                            </button>
                        @endif
                    </div>
                </div>

                <!-- Department & Organization Info -->
                <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">Department Chain</h3>
                        <div class="mt-2 flex items-center space-x-2">
                            @foreach($departmentChain as $dept)
                                <span class="text-sm text-gray-900">{{ $dept->name }}</span>
                                @if(!$loop->last)
                                    <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                @endif
                            @endforeach
                        </div>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">Position</h3>
                        <p class="mt-2 text-sm text-gray-900">{{ $user->position }}</p>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">Employee ID</h3>
                        <p class="mt-2 text-sm text-gray-900">{{ $user->employee_id }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-6 gap-6 mb-6">
            <!-- Total Assets Value -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <dt class="text-sm font-medium text-gray-500">Total Assets Value</dt>
                <dd class="mt-1 text-2xl font-semibold text-gray-900">
                    ${{ number_format($statistics['total_assets_value'], 2) }}
                </dd>
            </div>

            <!-- Active Assets -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <dt class="text-sm font-medium text-gray-500">Active Assets</dt>
                <dd class="mt-1 text-2xl font-semibold text-gray-900">
                    {{ $statistics['active_assets'] }}
                </dd>
            </div>

            <!-- Asset History -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <dt class="text-sm font-medium text-gray-500">Assets History ({{ $timeframe }}d)</dt>
                <dd class="mt-1 text-2xl font-semibold text-gray-900">
                    {{ $statistics['assets_history'] }}
                </dd>
            </div>

            <!-- Audits Conducted -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <dt class="text-sm font-medium text-gray-500">Audits ({{ $timeframe }}d)</dt>
                <dd class="mt-1 text-2xl font-semibold text-gray-900">
                    {{ $statistics['audits_conducted'] }}
                </dd>
            </div>

            <!-- Pending Requests -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <dt class="text-sm font-medium text-gray-500">Pending Requests</dt>
                <dd class="mt-1 text-2xl font-semibold text-gray-900">
                    {{ $statistics['pending_requests'] }}
                </dd>
            </div>

            <!-- Maintenance Items -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <dt class="text-sm font-medium text-gray-500">Maintenance Due</dt>
                <dd class="mt-1 text-2xl font-semibold text-gray-900">
                    {{ $statistics['maintenance_items'] }}
                </dd>
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

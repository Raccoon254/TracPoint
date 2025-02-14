<div class="space-y-6">
    <!-- Asset Utilization Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- Primary Stats Card -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Asset Utilization</h3>

            <div class="space-y-4">
                <!-- Overall Stats -->
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <span class="text-sm font-medium text-gray-500">Total Assigned</span>
                        <p class="text-2xl font-semibold text-gray-900">
                            {{ $assetUtilizationStats['total_assigned'] }}
                        </p>
                    </div>
                    <div>
                        <span class="text-sm font-medium text-gray-500">Currently Held</span>
                        <p class="text-2xl font-semibold text-gray-900">
                            {{ $assetUtilizationStats['currently_held'] }}
                        </p>
                    </div>
                </div>

                <!-- Return Rate -->
                <div>
                    <div class="flex items-center justify-between mb-1">
                        <span class="text-sm font-medium text-gray-500">Return Rate</span>
                        <span class="text-sm font-medium text-gray-900">{{ $assetUtilizationStats['return_rate'] }}%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2.5">
                        <div class="bg-emerald-600 h-2.5 rounded-full" style="width: {{ $assetUtilizationStats['return_rate'] }}%"></div>
                    </div>
                </div>

                <!-- Return Performance -->
                <div class="grid grid-cols-2 gap-4">
                    <div class="bg-green-50 rounded-lg p-3 text-center">
                        <span class="text-sm font-medium text-green-800">On Time</span>
                        <p class="text-lg font-semibold text-green-900">{{ $assetUtilizationStats['on_time_returns'] }}</p>
                    </div>
                    <div class="bg-red-50 rounded-lg p-3 text-center">
                        <span class="text-sm font-medium text-red-800">Late</span>
                        <p class="text-lg font-semibold text-red-900">{{ $assetUtilizationStats['late_returns'] }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Category Distribution -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Category Distribution</h3>

            <div class="space-y-4">
                @foreach($assetUtilizationStats['by_category'] as $category => $data)
                    <div>
                        <div class="flex items-center justify-between mb-1">
                            <span class="text-sm font-medium text-gray-700">{{ $category }}</span>
                            <span class="text-sm text-gray-500">{{ $data['count'] }} assets</span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <div class="flex-grow bg-gray-200 rounded-full h-2">
                                <div class="bg-blue-600 h-2 rounded-full"
                                     style="width: {{ ($data['count'] / $assetUtilizationStats['total_assigned']) * 100 }}%">
                                </div>
                            </div>
                            <span class="text-xs text-gray-500">${{ number_format($data['value']) }}</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Maintenance Overview -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Maintenance Overview</h3>

            <div class="space-y-4">
                <div class="grid grid-cols-3 gap-4">
                    <div class="text-center">
                        <span class="text-sm font-medium text-gray-500">Total</span>
                        <p class="text-xl font-semibold text-gray-900">{{ $assetUtilizationStats['maintenance_stats']['total'] }}</p>
                    </div>
                    <div class="text-center">
                        <span class="text-sm font-medium text-gray-500">Pending</span>
                        <p class="text-xl font-semibold text-yellow-600">{{ $assetUtilizationStats['maintenance_stats']['pending'] }}</p>
                    </div>
                    <div class="text-center">
                        <span class="text-sm font-medium text-gray-500">Completed</span>
                        <p class="text-xl font-semibold text-green-600">{{ $assetUtilizationStats['maintenance_stats']['completed'] }}</p>
                    </div>
                </div>

                <div class="pt-4 border-t border-gray-200">
                    <span class="text-sm font-medium text-gray-500">Average Assignment Duration</span>
                    <p class="text-lg font-semibold text-gray-900">{{ $assetUtilizationStats['avg_duration'] }} days</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity Timeline -->
    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Recent Activity</h3>
        </div>

        <div class="p-6">
            <div class="flow-root">
                <ul role="list" class="-mb-8">
                    @forelse($assetUtilizationStats['recent_activity'] as $activity)
                        <li>
                            <div class="relative pb-8">
                                @unless($loop->last)
                                    <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200"></span>
                                @endunless

                                <div class="relative flex items-start space-x-3">
                                    <div class="relative">
                                        <span class="h-8 w-8 rounded-full flex items-center justify-center ring-8 ring-white bg-{{ $activity['color'] }}-100">
                                            <i class="text-{{ $activity['color'] }}-600" data-lucide="{{ $activity['icon'] }}"></i>
                                        </span>
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <div class="text-sm font-medium text-gray-900">{{ $activity['description'] }}</div>
                                        <p class="mt-0.5 text-sm text-gray-500">{{ $activity['subtitle'] }}</p>
                                        <p class="mt-1 text-xs text-gray-500">{{ $activity['date']->diffForHumans() }}</p>
                                    </div>
                                </div>
                            </div>
                        </li>
                    @empty
                        <li class="text-center py-4 text-gray-500">No recent activity</li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
</div>

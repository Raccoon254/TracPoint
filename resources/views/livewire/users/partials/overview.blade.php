<div class="bg-white rounded-lg shadow-sm">
    <div class="p-6">
        <!-- Asset Utilization Chart -->
        <div class="mb-8">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Asset Utilization Overview</h3>
            <div class="bg-gray-50 rounded-lg p-4">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-sm font-medium text-gray-500">Utilization Rate</span>
                    <span class="text-sm font-medium text-gray-900">{{ $assetUtilizationRate }}%</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2.5">
                    <div class="bg-emerald-600 h-2.5 rounded-full" style="width: {{ $assetUtilizationRate }}%"></div>
                </div>
            </div>
        </div>

        <!-- Recent Activity -->
        <div>
            <h3 class="text-lg font-medium text-gray-900 mb-4">Recent Activity</h3>
            <div class="space-y-4">
                @forelse($audits->take(3) as $audit)
                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-900">
                                    Audited {{ $audit->asset->name }}
                                </p>
                                <p class="text-sm text-gray-500">
                                    {{ $audit->audit_date->diffForHumans() }}
                                </p>
                            </div>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                {{ $audit->asset->category->name }}
                            </span>
                        </div>
                    </div>
                @empty
                    <div>
                        @include('partials.empty', [
                            'title' => 'No recent activity',
                            'message' => 'No recent activity found for this user.',
                            'icon' => 'user'
                        ])
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

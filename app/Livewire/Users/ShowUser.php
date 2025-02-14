<?php

namespace App\Livewire\Users;

use App\Models\User;
use App\Models\Asset;
use Illuminate\View\View;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;

class ShowUser extends Component
{
    use WithPagination;

    public User $user;
    public $activeTab = 'overview';
    public $timeframe = '30'; // days for analytics

    // Asset Filters
    public $assetStatus = '';
    public $assetSearch = '';

    // Audit Filters
    public $auditDateRange = '';

    protected $queryString = [
        'activeTab' => ['except' => 'overview'],
        'timeframe' => ['except' => '30'],
    ];

    public function mount(User $user): void
    {
        // Check if current user has access to view this user
        if (!auth()->user()->role === 'super_admin' &&
            auth()->user()->organization_id !== $user->organization_id) {
            abort(403);
        }

        $this->user = $user;
    }

    public function getStatisticsProperty()
    {
        $timeframeDays = (int)$this->timeframe;
        $startDate = now()->subDays($timeframeDays);

        return [
            'total_assets_value' => $this->user->assignedAssets()
                ->sum('value'),
            'active_assets' => $this->user->assignedAssets()
                ->where('status', 'assigned')
                ->count(),
            'assets_history' => $this->user->assignedAssets()
                ->where('assigned_date', '>=', $startDate)
                ->count(),
            'audits_conducted' => $this->user->audits()
                ->where('audit_date', '>=', $startDate)
                ->count(),
            'pending_requests' => $this->user->assetRequests()
                ->where('status', 'pending')
                ->count(),
            'maintenance_items' => Asset::where('assigned_to', $this->user->id)
                ->whereHas('maintenanceRecords', function($query) {
                    $query->where('status', '!=', 'completed');
                })->count(),
        ];
    }

    public function getAssignedAssetsProperty()
    {
        return $this->user->assignedAssets()
            ->when($this->assetStatus, function($query) {
                $query->where('status', $this->assetStatus);
            })
            ->when($this->assetSearch, function($query) {
                $query->where(function($q) {
                    $q->where('name', 'like', "%{$this->assetSearch}%")
                        ->orWhere('asset_code', 'like', "%{$this->assetSearch}%");
                });
            })
            ->with(['category', 'department', 'maintenanceRecords'])
            ->latest('assigned_date')
            ->paginate(5);
    }

    public function getAuditsProperty()
    {
        return $this->user->audits()
            ->when($this->auditDateRange, function($query) {
                // Add date range filter logic
            })
            ->with(['asset', 'asset.category'])
            ->latest('audit_date')
            ->paginate(5);
    }

    public function getAssetRequestsProperty()
    {
        return $this->user->assetRequests()
            ->with(['category'])
            ->latest()
            ->paginate(5);
    }

    public function getDepartmentChainProperty(): array
    {
        $chain = [];
        $department = $this->user->department;

        while ($department) {
            array_unshift($chain, $department);
            $department = $department->parent;
        }

        return $chain;
    }

    public function getOrganizationAssetsCountProperty()
    {
        return Asset::where('organization_id', $this->user->organization_id)->count();
    }

    public function getAssetUtilizationRateProperty(): float|int
    {
        $totalAssigned = $this->user->assignedAssets()->count();
        $totalReturned = $this->user->assignedAssets()
            ->where('status', '!=', 'assigned')
            ->count();

        return $totalAssigned > 0
            ? round(($totalReturned / $totalAssigned) * 100, 2)
            : 0;
    }

    #[Computed]
    public function assignedAssets()
    {
        return $this->user->assignedAssets()
            ->when($this->assetStatus, function($query) {
                $query->where('status', $this->assetStatus);
            })
            ->when($this->assetSearch, function($query) {
                $query->where(function($q) {
                    $q->where('name', 'like', "%{$this->assetSearch}%")
                        ->orWhere('asset_code', 'like', "%{$this->assetSearch}%");
                });
            })
            ->with(['category', 'department', 'maintenanceRecords'])
            ->latest('assigned_date')
            ->paginate(5);
    }

    #[Computed]
    public function audits()
    {
        return $this->user->audits()
            ->when($this->auditDateRange, function($query) {
                // Add date range filter logic
            })
            ->with(['asset', 'asset.category'])
            ->latest('audit_date')
            ->paginate(5);
    }

    #[Computed]
    public function assetRequests()
    {
        return $this->user->assetRequests()
            ->with(['category'])
            ->latest()
            ->paginate(5);
    }

    public function getAssetUtilizationStatsProperty(): array
    {
        $assignedAssets = $this->user->assignedAssets();
        $startDate = now()->subDays((int)$this->timeframe);

        return [
            'total_assigned' => $assignedAssets->count(),
            'currently_held' => $assignedAssets->where('status', 'assigned')->count(),
            'return_rate' => $this->getAssetUtilizationRateProperty(),
            'on_time_returns' => $assignedAssets
                ->whereNotNull('expected_return_date')
                ->where('status', '!=', 'assigned')
                ->where('updated_at', '<=', DB::raw('expected_return_date'))
                ->count(),
            'late_returns' => $assignedAssets
                ->whereNotNull('expected_return_date')
                ->where('status', '!=', 'assigned')
                ->where('updated_at', '>', DB::raw('expected_return_date'))
                ->count(),
            'by_category' => $assignedAssets
                ->with('category')
                ->get()
                ->groupBy('category.name')
                ->map(fn($items) => [
                    'count' => $items->count(),
                    'value' => $items->sum('value')
                ]),
            'maintenance_stats' => [
                'total' => $assignedAssets->withCount('maintenanceRecords')->get()->sum('maintenance_records_count'),
                'pending' => $assignedAssets->whereHas('maintenanceRecords', function($q) {
                    $q->where('status', '!=', 'completed');
                })->count(),
                'completed' => $assignedAssets->whereHas('maintenanceRecords', function($q) {
                    $q->where('status', 'completed');
                })->count()
            ],
            'avg_duration' => round($assignedAssets
                ->whereNotNull(['assigned_date', 'expected_return_date'])
                ->get()
                ->avg(fn($asset) => $asset->assigned_date->diffInDays($asset->expected_return_date)), 1),
            'recent_activity' => $this->getRecentAssetActivityProperty(),
            'utilization_trend' => $this->getUtilizationTrendProperty(),
        ];
    }

    protected function getUtilizationTrendProperty()
    {
        $startDate = now()->subDays((int)$this->timeframe);

        return $this->user->assignedAssets()
            ->where('assigned_date', '>=', $startDate)
            ->select(DB::raw('DATE(assigned_date) as date'), DB::raw('COUNT(*) as count'))
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->map(fn($item) => [
                'date' => $item->date,
                'count' => $item->count
            ]);
    }

    protected function getRecentAssetActivityProperty()
    {
        $activities = collect();

        // Add asset assignments
        $assignments = $this->user->assignedAssets()
            ->with(['category', 'department'])
            ->latest('assigned_date')
            ->take(5)
            ->get()
            ->map(function($asset) {
                return [
                    'type' => 'assignment',
                    'icon' => 'clipboard-check',
                    'color' => 'blue',
                    'description' => "Assigned {$asset->category->name} ({$asset->asset_code})",
                    'subtitle' => "Department: {$asset->department->name}",
                    'date' => $asset->assigned_date,
                ];
            });

        // Add audits
        $audits = $this->user->audits()
            ->with('asset')
            ->latest('audit_date')
            ->take(5)
            ->get()
            ->map(function($audit) {
                return [
                    'type' => 'audit',
                    'icon' => 'clipboard-list',
                    'color' => 'yellow',
                    'description' => "Audited {$audit->asset->name}",
                    'subtitle' => $audit->discrepancies ? 'Discrepancies found' : 'No issues found',
                    'date' => $audit->audit_date,
                ];
            });

        // Add maintenance records
        $maintenance = Asset::where('assigned_to', $this->user->id)
            ->whereHas('maintenanceRecords')
            ->with(['maintenanceRecords' => function($q) {
                $q->latest()->limit(5);
            }])
            ->get()
            ->flatMap(function($asset) {
                return $asset->maintenanceRecords->map(function($record) use ($asset) {
                    return [
                        'type' => 'maintenance',
                        'icon' => 'wrench',
                        'color' => 'red',
                        'description' => "Maintenance: {$asset->name}",
                        'subtitle' => ucfirst($record->maintenance_type),
                        'date' => $record->maintenance_date,
                    ];
                });
            });

        return $activities->concat($assignments)
            ->concat($audits)
            ->concat($maintenance)
            ->sortByDesc('date')
            ->take(10)
            ->values();
    }

    public function render(): View
    {
        return view('livewire.users.show-user', [
            'assignedAssets' => $this->assignedAssets(),
            'audits' => $this->audits(),
            'assetRequests' => $this->assetRequests(),
            'statistics' => $this->getStatisticsProperty(),
            'departmentChain' => $this->getDepartmentChainProperty(),
            'organizationAssetsCount' => $this->getOrganizationAssetsCountProperty(),
            'assetUtilizationRate' => $this->getAssetUtilizationRateProperty(),
            'assetUtilizationStats' => $this->getAssetUtilizationStatsProperty(),
        ]);
    }
}

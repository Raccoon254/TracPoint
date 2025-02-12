<?php

namespace App\Livewire\Users;

use App\Models\User;
use App\Models\Asset;
use Illuminate\View\View;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithPagination;

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

    public function getDepartmentChainProperty()
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

    public function getAssetUtilizationRateProperty()
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
        ]);
    }
}

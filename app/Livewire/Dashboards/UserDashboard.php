<?php

namespace App\Livewire\Dashboards;

use App\Models\Asset;
use App\Models\AssetRequest;
use App\Models\MaintenanceRecord;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\Attributes\Computed;

class UserDashboard extends Component
{
    public array $stats = [];
    public User $user;
    public $assignedAssets = [];
    public $pendingRequests = [];
    public $maintenanceSchedule = [];
    public $recentActivities = [];

    public function mount(): void
    {
        $this->user = auth()->user();
        $this->calculateStats();
        $this->loadAssignedAssets();
        $this->loadPendingRequests();
        $this->loadMaintenanceSchedule();
        $this->loadRecentActivities();
    }

    protected function calculateStats(): void
    {
        $this->stats = [
            'total_assigned_assets' => Asset::where('assigned_to', $this->user->id)
                ->where('status', 'assigned')
                ->count(),

            'pending_returns' => Asset::where('assigned_to', $this->user->id)
                ->where('status', 'assigned')
                ->whereNotNull('expected_return_date')
                ->where('expected_return_date', '<=', now()->addDays(7))
                ->count(),

            'pending_requests' => AssetRequest::where('requester_id', $this->user->id)
                ->whereIn('status', ['pending', 'approved'])
                ->count(),

            'maintenance_due' => MaintenanceRecord::whereHas('asset', function($query) {
                $query->where('assigned_to', $this->user->id);
            })
                ->where('status', '!=', 'completed')
                ->where('next_maintenance_date', '<=', now()->addDays(7))
                ->count(),
        ];
    }

    protected function loadAssignedAssets(): void
    {
        $this->assignedAssets = Asset::where('assigned_to', $this->user->id)
            ->where('status', 'assigned')
            ->with(['category:id,name', 'department:id,name'])
            ->select([
                'id',
                'name',
                'asset_code',
                'category_id',
                'current_department_id',
                'assigned_date',
                'expected_return_date',
                'condition'
            ])
            ->orderBy('expected_return_date')
            ->get();
    }

    protected function loadPendingRequests(): void
    {
        $this->pendingRequests = AssetRequest::where('requester_id', $this->user->id)
            ->whereIn('status', ['pending', 'approved'])
            ->with(['category:id,name'])
            ->select([
                'id',
                'category_id',
                'purpose',
                'status',
                'required_from',
                'required_until',
                'created_at'
            ])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
    }

    protected function loadMaintenanceSchedule(): void
    {
        $this->maintenanceSchedule = MaintenanceRecord::whereHas('asset', function($query) {
            $query->where('assigned_to', $this->user->id);
        })
            ->where('status', '!=', 'completed')
            ->where('next_maintenance_date', '<=', now()->addDays(30))
            ->with(['asset:id,name,asset_code'])
            ->select([
                'id',
                'asset_id',
                'maintenance_type',
                'next_maintenance_date',
                'status',
                'description'
            ])
            ->orderBy('next_maintenance_date')
            ->limit(5)
            ->get();
    }

    protected function loadRecentActivities(): void
    {
        // Asset assignments and returns
        $assetActivities = DB::table('assets')
            ->where('assigned_to', $this->user->id)
            ->orWhere(function($query) {
                $query->where('status', 'available')
                    ->whereRaw('assigned_to IS NULL')
                    ->where('assigned_to', $this->user->id);
            })
            ->select(
                DB::raw("'asset' as activity_type"),
                'name as subject_name',
                'status as action',
                'assigned_date as activity_date',
                DB::raw('NULL as additional_info')
            );

        // Asset requests
        $requestActivities = DB::table('asset_requests')
            ->where('requester_id', $this->user->id)
            ->select(
                DB::raw("'request' as activity_type"),
                'purpose as subject_name',
                'status as action',
                'created_at as activity_date',
                'category_id as additional_info'
            );

        // Maintenance records
        $maintenanceActivities = DB::table('maintenance_records')
            ->join('assets', 'maintenance_records.asset_id', '=', 'assets.id')
            ->where('assets.assigned_to', $this->user->id)
            ->select(
                DB::raw("'maintenance' as activity_type"),
                'assets.name as subject_name',
                'maintenance_records.maintenance_type as action',
                'maintenance_records.maintenance_date as activity_date',
                'maintenance_records.status as additional_info'
            );

        // Combine and get recent activities
        $this->recentActivities = $assetActivities
            ->union($requestActivities)
            ->union($maintenanceActivities)
            ->orderBy('activity_date', 'desc')
            ->limit(10)
            ->get();
    }

    public function refreshData(): void
    {
        $this->calculateStats();
        $this->loadAssignedAssets();
        $this->loadPendingRequests();
        $this->loadMaintenanceSchedule();
        $this->loadRecentActivities();
    }

    #[Computed]
    public function upcomingReturns(): array
    {
        return $this->assignedAssets
            ->where('expected_return_date', '!=', null)
            ->where('expected_return_date', '<=', now()->addDays(7))
            ->sortBy('expected_return_date')
            ->take(5)
            ->toArray();
    }

    #[Computed]
    public function assetsByCategory(): array
    {
        return $this->assignedAssets
            ->groupBy('category.name')
            ->map(function($assets) {
                return count($assets);
            })
            ->toArray();
    }

    public function render(): View
    {
        return view('livewire.dashboards.user-dashboard');
    }
}

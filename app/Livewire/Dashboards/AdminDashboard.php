<?php

namespace App\Livewire\Dashboards;

use App\Models\Asset;
use App\Models\AssetRequest;
use App\Models\Department;
use App\Models\MaintenanceRecord;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\Attributes\Computed;

class AdminDashboard extends Component
{
    public array $stats = [];
    public User $user;
    public $selectedDepartment = null;
    public $departments = [];
    public $recentActivities = [];
    public $maintenanceAlerts = [];

    public function mount(): void
    {
        $this->user = auth()->user();
        $this->loadDepartments();
        $this->calculateStats();
        $this->loadRecentActivities();
        $this->loadMaintenanceAlerts();
    }

    protected function loadDepartments(): void
    {
        // Get all departments in the organization
        $this->departments = Department::where('organization_id', $this->user->organization_id)
            ->orderBy('name')
            ->get(['id', 'name'])
            ->toArray();
        //dd($this->departments, $this->user);
    }

    public function updatedSelectedDepartment(): void
    {
        $this->calculateStats();
    }

    protected function calculateStats(): void
    {
        $organizationId = $this->user->organization_id;
        $query = Asset::whereHas('department', function($query) use ($organizationId) {
            $query->where('organization_id', $organizationId);
        });

        // If department is selected, filter by it
        if ($this->selectedDepartment) {
            $query->where('current_department_id', $this->selectedDepartment);
        }

        // Calculate asset statuses for utilization
        $assetStatuses = $query->select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        // Get user counts
        $userQuery = User::where('organization_id', $organizationId);
        if ($this->selectedDepartment) {
            $userQuery->where('department_id', $this->selectedDepartment);
        }

        $this->stats = [
            'total_assets' => array_sum($assetStatuses),
            'total_users' => $userQuery->count(),
            'pending_requests' => AssetRequest::whereHas('requester', function($query) use ($organizationId) {
                $query->where('organization_id', $organizationId);
            })
                ->when($this->selectedDepartment, function($query) {
                    $query->whereHas('requester', function($q) {
                        $q->where('department_id', $this->selectedDepartment);
                    });
                })
                ->where('status', 'pending')
                ->count(),
            'maintenance_due' => MaintenanceRecord::whereHas('asset.department', function($query) use ($organizationId) {
                $query->where('organization_id', $organizationId);
            })
                ->when($this->selectedDepartment, function($query) {
                    $query->whereHas('asset', function($q) {
                        $q->where('current_department_id', $this->selectedDepartment);
                    });
                })
                ->where('status', '!=', 'completed')
                ->where('next_maintenance_date', '<=', now())
                ->count(),
            'asset_value' => $query->sum('value'),
            'asset_utilization' => [
                'assigned' => $assetStatuses['assigned'] ?? 0,
                'available' => $assetStatuses['available'] ?? 0,
                'in_maintenance' => $assetStatuses['in_maintenance'] ?? 0,
                'retired' => $assetStatuses['retired'] ?? 0,
            ],
        ];
    }

    protected function loadRecentActivities(): void
    {
        // Asset activities
        $assetActivities = DB::table('assets')
            ->leftJoin('users', 'assets.assigned_to', '=', 'users.id')
            ->leftJoin('departments', 'assets.current_department_id', '=', 'departments.id')
            ->where('assets.organization_id', $this->user->organization_id)
            ->when($this->selectedDepartment, function($query) {
                $query->where('assets.current_department_id', $this->selectedDepartment);
            })
            ->select(
                DB::raw("'asset' as activity_type"),
                'assets.name as subject_name',
                'users.name as related_user',
                'departments.name as department_name',
                'assets.status as action',
                'assets.updated_at'
            );

        // User activities (new users added)
        $userActivities = DB::table('users')
            ->leftJoin('departments', 'users.department_id', '=', 'departments.id')
            ->where('users.organization_id', $this->user->organization_id)
            ->when($this->selectedDepartment, function($query) {
                $query->where('users.department_id', $this->selectedDepartment);
            })
            ->select(
                DB::raw("'user' as activity_type"),
                'users.name as subject_name',
                DB::raw('NULL as related_user'),
                'departments.name as department_name',
                DB::raw("'joined' as action"),
                'users.created_at as updated_at'
            );

        // Maintenance activities
        $maintenanceActivities = DB::table('maintenance_records')
            ->join('assets', 'maintenance_records.asset_id', '=', 'assets.id')
            ->leftJoin('users', 'maintenance_records.performed_by', '=', 'users.id')
            ->leftJoin('departments', 'assets.current_department_id', '=', 'departments.id')
            ->where('assets.organization_id', $this->user->organization_id)
            ->when($this->selectedDepartment, function($query) {
                $query->where('assets.current_department_id', $this->selectedDepartment);
            })
            ->select(
                DB::raw("'maintenance' as activity_type"),
                'assets.name as subject_name',
                'users.name as related_user',
                'departments.name as department_name',
                'maintenance_records.maintenance_type as action',
                'maintenance_records.created_at as updated_at'
            );

        // Combine all activities
        $this->recentActivities = $assetActivities
            ->union($userActivities)
            ->union($maintenanceActivities)
            ->orderBy('updated_at', 'desc')
            ->limit(10)
            ->get();
    }

    protected function loadMaintenanceAlerts(): void
    {
        $this->maintenanceAlerts = MaintenanceRecord::whereHas('asset.department', function($query) {
            $query->where('organization_id', $this->user->organization_id);
        })
            ->when($this->selectedDepartment, function($query) {
                $query->whereHas('asset', function($q) {
                    $q->where('current_department_id', $this->selectedDepartment);
                });
            })
            ->where('status', '!=', 'completed')
            ->where('next_maintenance_date', '<=', now()->addDays(7))
            ->with(['asset:id,name,current_department_id', 'asset.department:id,name'])
            ->orderBy('next_maintenance_date')
            ->limit(5)
            ->get();
    }

    #[Computed]
    public function selectedDepartmentName(): ?string
    {
        if (!$this->selectedDepartment) {
            return 'All Departments';
        }

        $department = collect($this->departments)
            ->firstWhere('id', $this->selectedDepartment);
        return $department ? $department['name'] : 'Unknown Department';
    }

    public function refreshData(): void
    {
        sleep(10); // Simulate a long-running process
        $this->calculateStats();
        $this->loadRecentActivities();
        $this->loadMaintenanceAlerts();
    }

    public function render(): View
    {
        return view('livewire.dashboards.admin-dashboard');
    }
}

<?php

namespace App\Livewire\Dashboards;

use App\Models\Asset;
use App\Models\AssetRequest;
use App\Models\Audit;
use App\Models\Department;
use App\Models\User;
use Illuminate\View\View;
use Livewire\Component;

class MainDashboard extends Component
{
    public array $stats = [];
    public User $user;

    public function mount(): void
    {
        $this->stats = $this->calculateStats();
        $this->user = auth()->user();
    }


    public function calculateStats(): array
    {
        // Common stats calculations
        $user = auth()->user();

        // Role-specific stats
        return match($user->role) {
            'super_admin' => [
                'total_users' => User::count(),
                'total_assets' => Asset::count(),
                'total_departments' => Department::count(),
                'pending_requests' => AssetRequest::where('status', 'pending')->count(),
                // Change maintenance calculation to use MaintenanceRecord
                'maintenance_due' => Asset::whereHas('maintenanceRecords', function($query) {
                    $query->where('next_maintenance_date', '<=', now())
                        ->where('status', '!=', 'completed');
                })->count(),
                'recent_audits' => Audit::with(['asset', 'auditor'])
                    ->latest()
                    ->take(5)
                    ->get(),
            ],
            'admin' => [
                'department_assets' => Asset::where('current_department_id', $user->department_id)->count(),
                'department_users' => User::where('department_id', $user->department_id)->count(),
                'pending_requests' => AssetRequest::where('status', 'pending')
                    ->whereHas('requester', fn($q) => $q->where('department_id', $user->department_id))
                    ->count(),
                // Department-specific maintenance due
                'maintenance_due' => Asset::where('current_department_id', $user->department_id)
                    ->whereHas('maintenanceRecords', function($query) {
                        $query->where('next_maintenance_date', '<=', now())
                            ->where('status', '!=', 'completed');
                    })->count(),
            ],
            'auditor' => [
                'completed_audits' => Audit::where('auditor_id', $user->id)->count(),
                // Change pending audits calculation to use last_audit_date from Audit model
                'pending_audits' => Asset::whereDoesntHave('audits', function($query) {
                    $query->where('audit_date', '>', now()->subMonths(3));
                })->count(),
                'recent_audits' => Audit::with('asset')
                    ->where('auditor_id', $user->id)
                    ->latest()
                    ->take(5)
                    ->get(),
            ],
            'user' => [
                'assigned_assets' => Asset::where('assigned_to', $user->id)->count(),
                'pending_requests' => AssetRequest::where('requester_id', $user->id)
                    ->where('status', 'pending')
                    ->count(),
                'upcoming_returns' => Asset::where('assigned_to', $user->id)
                    ->whereNotNull('expected_return_date')
                    ->whereDate('expected_return_date', '<=', now()->addDays(7))
                    ->count(),
            ],
            default => [],
        };
    }

    public function render(): View
    {
        return view('livewire.dashboards.main-dashboard');
    }
}

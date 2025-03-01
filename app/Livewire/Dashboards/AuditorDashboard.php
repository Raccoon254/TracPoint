<?php

namespace App\Livewire\Dashboards;

use App\Models\Asset;
use App\Models\Audit;
use App\Models\User;
use App\Models\Department;
use App\Models\Organization;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\Attributes\Computed;

class AuditorDashboard extends Component
{
    public array $stats = [];
    public User $user;
    public $selectedDepartment = null;
    public $departments = [];
    public $recentAudits = [];
    public $upcomingAudits = [];
    public $auditsByCondition = [];

    public function mount(): void
    {
        $this->user = auth()->user();
        $this->loadDepartments();
        $this->calculateStats();
        $this->loadRecentAudits();
        $this->loadUpcomingAudits();
        $this->loadAuditsByCondition();
    }

    protected function loadDepartments(): void
    {
        // Get all departments in the organization
        $this->departments = Department::where('organization_id', $this->user->organization_id)
            ->orderBy('name')
            ->get(['id', 'name'])
            ->toArray();
    }

    public function updatedSelectedDepartment(): void
    {
        $this->calculateStats();
        $this->loadRecentAudits();
        $this->loadUpcomingAudits();
        $this->loadAuditsByCondition();
    }

    protected function calculateStats(): array
    {
        $user = $this->user;
        $organizationId = $user->organization_id;

        // Base query for completed audits
        $completedAuditsQuery = Audit::where('auditor_id', $user->id);

        // Base query for pending audits
        $pendingAuditsQuery = Asset::whereDoesntHave('audits', function($query) {
            $query->where('audit_date', '>', now()->subMonths(3));
        });

        // If department is selected, filter the queries
        if ($this->selectedDepartment) {
            $completedAuditsQuery->whereHas('asset', function($query) {
                $query->where('current_department_id', $this->selectedDepartment);
            });

            $pendingAuditsQuery->where('current_department_id', $this->selectedDepartment);
        }

        // Query for assets by condition
        $assetsByCondition = Asset::when($this->selectedDepartment, function($query) {
            $query->where('current_department_id', $this->selectedDepartment);
        })
            ->select('condition', DB::raw('count(*) as count'))
            ->groupBy('condition')
            ->pluck('count', 'condition')
            ->toArray();

        // Calculate total assets to audit
        $totalAssetsToAudit = $pendingAuditsQuery->count();

        // Calculate completed audits
        $completedAuditsCount = $completedAuditsQuery->count();

        // Calculate last month's audits
        $lastMonthAudits = Audit::where('auditor_id', $user->id)
            ->where('audit_date', '>=', now()->subMonth())
            ->when($this->selectedDepartment, function($query) {
                $query->whereHas('asset', function($q) {
                    $q->where('current_department_id', $this->selectedDepartment);
                });
            })
            ->count();

        // Calculate this week's audits
        $thisWeekAudits = Audit::where('auditor_id', $user->id)
            ->where('audit_date', '>=', now()->startOfWeek())
            ->when($this->selectedDepartment, function($query) {
                $query->whereHas('asset', function($q) {
                    $q->where('current_department_id', $this->selectedDepartment);
                });
            })
            ->count();

        $this->stats = [
            'completed_audits' => $completedAuditsCount,
            'pending_audits' => $totalAssetsToAudit,
            'last_month_audits' => $lastMonthAudits,
            'this_week_audits' => $thisWeekAudits,
            'assets_by_condition' => $assetsByCondition,
            'audits_with_discrepancies' => Audit::where('auditor_id', $user->id)
                ->whereNotNull('discrepancies')
                ->when($this->selectedDepartment, function($query) {
                    $query->whereHas('asset', function($q) {
                        $q->where('current_department_id', $this->selectedDepartment);
                    });
                })
                ->count(),
        ];

        return $this->stats;
    }

    protected function loadRecentAudits(): void
    {
        $this->recentAudits = Audit::with(['asset', 'asset.department'])
            ->where('auditor_id', $this->user->id)
            ->when($this->selectedDepartment, function($query) {
                $query->whereHas('asset', function($q) {
                    $q->where('current_department_id', $this->selectedDepartment);
                });
            })
            ->latest('audit_date')
            ->take(5)
            ->get();
    }

    protected function loadUpcomingAudits(): void
    {
        // Get assets that haven't been audited in the last 3 months
        $this->upcomingAudits = Asset::with('department')
            ->whereDoesntHave('audits', function($query) {
                $query->where('audit_date', '>', now()->subMonths(3));
            })
            ->when($this->selectedDepartment, function($query) {
                $query->where('current_department_id', $this->selectedDepartment);
            })
            ->take(5)
            ->get();
    }

    protected function loadAuditsByCondition(): void
    {
        $this->auditsByCondition = Audit::where('auditor_id', $this->user->id)
            ->when($this->selectedDepartment, function($query) {
                $query->whereHas('asset', function($q) {
                    $q->where('current_department_id', $this->selectedDepartment);
                });
            })
            ->select('new_condition', DB::raw('count(*) as count'))
            ->groupBy('new_condition')
            ->get()
            ->toArray();
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
        $this->calculateStats();
        $this->loadRecentAudits();
        $this->loadUpcomingAudits();
        $this->loadAuditsByCondition();
    }

    public function render(): View
    {
        return view('livewire.dashboards.auditor-dashboard');
    }
}

<?php

namespace App\Livewire\Audits;

use App\Models\Audit;
use App\Models\Asset;
use App\Models\Department;
use App\Models\User;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    // Filters
    public string $search = '';
    public $asset_id = '';
    public $department_id = '';
    public $auditor_id = '';
    public $location_verified = '';
    public $date_from = '';
    public $date_to = '';
    public $has_discrepancies = '';

    // Sorting
    public $sortField = 'audit_date';
    public $sortDirection = 'desc';

    // Filter options
    public $assets;
    public $departments;
    public $auditors;
    public $locationVerifiedOptions = ['1' => 'Yes', '0' => 'No'];
    public $discrepancyOptions = ['1' => 'Yes', '0' => 'No'];

    protected $queryString = [
        'search' => ['except' => ''],
        'asset_id' => ['except' => ''],
        'department_id' => ['except' => ''],
        'auditor_id' => ['except' => ''],
        'location_verified' => ['except' => ''],
        'date_from' => ['except' => ''],
        'date_to' => ['except' => ''],
        'has_discrepancies' => ['except' => ''],
        'sortField' => ['except' => 'audit_date'],
        'sortDirection' => ['except' => 'desc'],
    ];

    public function mount(): void
    {
        $user = auth()->user();
        $this->departments = $this->getDepartmentList($user);
        $this->assets = $this->getAssetList($user);
        $this->auditors = $this->getAuditorList($user);
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function sortBy($field): void
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function resetFilters(): void
    {
        $this->reset([
            'search',
            'asset_id',
            'department_id',
            'auditor_id',
            'location_verified',
            'date_from',
            'date_to',
            'has_discrepancies'
        ]);
    }

    public function updatedAssetId(): void
    {
        $this->resetPage();
    }

    public function updatedDepartmentId(): void
    {
        $this->resetPage();
    }

    public function updatedAuditorId(): void
    {
        $this->resetPage();
    }

    public function updatedLocationVerified(): void
    {
        $this->resetPage();
    }

    public function updatedDateFrom(): void
    {
        $this->resetPage();
    }

    public function updatedDateTo(): void
    {
        $this->resetPage();
    }

    public function updatedHasDiscrepancies(): void
    {
        $this->resetPage();
    }

    public function render(): View
    {
        $user = auth()->user();

        return view('livewire.audits.index', [
            'audits' => $this->getAudits($user),
        ]);
    }

    /**
     * Get filtered audits based on user role and filters
     */
    private function getAudits(User $user)
    {
        return Audit::query()
            ->when($this->search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->whereHas('asset', function($assetQuery) use ($search) {
                        $assetQuery->where('name', 'like', '%' . $search . '%')
                            ->orWhere('asset_code', 'like', '%' . $search . '%');
                    })
                        ->orWhereHas('auditor', function($auditorQuery) use ($search) {
                            $auditorQuery->where('name', 'like', '%' . $search . '%');
                        });
                });
            })
            ->when($this->asset_id, function ($query, $assetId) {
                $query->where('asset_id', $assetId);
            })
            ->when($this->department_id, function ($query, $departmentId) {
                $query->whereHas('asset', function($q) use ($departmentId) {
                    $q->where('current_department_id', $departmentId);
                });
            })
            ->when($this->auditor_id, function ($query, $auditorId) {
                $query->where('auditor_id', $auditorId);
            })
            ->when($this->location_verified !== '', function ($query) {
                $query->where('location_verified', $this->location_verified);
            })
            ->when($this->date_from, function ($query, $dateFrom) {
                $query->whereDate('audit_date', '>=', $dateFrom);
            })
            ->when($this->date_to, function ($query, $dateTo) {
                $query->whereDate('audit_date', '<=', $dateTo);
            })
            ->when($this->has_discrepancies !== '', function ($query) {
                if ($this->has_discrepancies) {
                    $query->whereNotNull('discrepancies')->where('discrepancies', '!=', '');
                } else {
                    $query->where(function($q) {
                        $q->whereNull('discrepancies')->orWhere('discrepancies', '');
                    });
                }
            })
            // Filter by organization if not super admin
            ->when($user->role !== 'super_admin', function ($query) use ($user) {
                $query->whereHas('asset', function ($q) use ($user) {
                    $q->whereHas('organization', function($orgQuery) use ($user) {
                        $orgQuery->where('id', $user->organization_id);
                    });
                });
            })
            // Filter for auditors to only see their own audits
            ->when($user->role === 'auditor', function ($query) use ($user) {
                $query->where('auditor_id', $user->id);
            })
            ->with(['asset', 'asset.department', 'auditor'])
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(10);
    }

    /**
     * Get the list of departments that belong to the user's organization.
     */
    private function getDepartmentList(User $user)
    {
        return Department::when($user->role !== 'super_admin', function ($query) use ($user) {
            $query->where('organization_id', $user->organization_id);
        })
            ->orderBy('name')
            ->get();
    }

    /**
     * Get the list of assets that belong to the user's organization.
     */
    private function getAssetList(User $user)
    {
        return Asset::when($user->role !== 'super_admin', function ($query) use ($user) {
            $query->where('organization_id', $user->organization_id);
        })
            ->orderBy('name')
            ->get();
    }

    /**
     * Get the list of auditors that belong to the user's organization.
     */
    private function getAuditorList(User $user)
    {
        return User::where('role', 'auditor')
            ->when($user->role !== 'super_admin', function ($query) use ($user) {
                $query->where('organization_id', $user->organization_id);
            })
            ->orderBy('name')
            ->get();
    }
}

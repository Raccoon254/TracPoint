<?php

namespace App\Livewire\Assets;

use App\Models\Asset;
use App\Models\AssetCategory;
use App\Models\Department;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\WithPagination;

class ViewAll extends Component
{
    use WithPagination;

    // Filters
    public string $search = '';
    public $category_id = '';
    public $department_id = '';
    public $status = '';
    public $condition = '';

    // Sorting
    public $sortField = 'name';
    public $sortDirection = 'asc';

    // Filter options
    public $categories;
    public $departments;
    public $statusOptions = ['available', 'assigned', 'in_maintenance', 'retired'];
    public $conditionOptions = ['new', 'good', 'fair', 'poor'];

    protected $queryString = [
        'search' => ['except' => ''],
        'category_id' => ['except' => ''],
        'department_id' => ['except' => ''],
        'status' => ['except' => ''],
        'condition' => ['except' => ''],
        'sortField' => ['except' => 'name'],
        'sortDirection' => ['except' => 'asc'],
    ];

    public function mount(): void
    {
        $this->categories = AssetCategory::all();
        $this->departments = Department::all();
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
        $this->reset(['search', 'category_id', 'department_id', 'status', 'condition']);
    }

    public function updatingCategoryId(): void
    {
        $this->resetPage();
    }

    public function updatingDepartmentId(): void
    {
        $this->resetPage();
    }

    public function updatingStatus(): void
    {
        $this->resetPage();
    }

    public function updatingCondition(): void
    {
        $this->resetPage();
    }


    public function render(): View
    {
        return view('livewire.assets.view-all', [
            'assets' =>  Asset::query()
                ->when($this->search, function ($query, $search) {
                    $query->where(function ($q) use ($search) {
                        $q->where('name', 'like', '%' . $search . '%')
                            ->orWhere('asset_code', 'like', '%' . $search . '%');
                    });
                })
                ->orderBy($this->sortField, $this->sortDirection)
                ->with(['category', 'department', 'assignedUser'])
                ->paginate(10),
        ]);
    }
}

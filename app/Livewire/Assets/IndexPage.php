<?php

namespace App\Livewire\Assets;

use App\Models\Asset;
use App\Models\AssetCategory;
use App\Models\Department;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\WithPagination;

class IndexPage extends Component
{
    use WithPagination;

    // Filters
    public $search = '';
    public $selectedCategories = [];
    public $selectedDepartments = [];
    public $selectedStatuses = [];
    public $selectedConditions = [];
    public $priceRange = [0, 100000];
    public $sortBy = 'newest';
    public $view = 'grid';

    // Filter options
    public $categories;
    public $departments;
    public $statusOptions = ['available', 'assigned', 'in_maintenance', 'retired'];
    public $conditionOptions = ['new', 'good', 'fair', 'poor'];
    public $sortOptions = [
        'newest' => 'Newest First',
        'oldest' => 'Oldest First',
        'name_asc' => 'Name (A-Z)',
        'name_desc' => 'Name (Z-A)',
        'value_asc' => 'Value (Low to High)',
        'value_desc' => 'Value (High to Low)',
    ];

    protected $queryString = [
        'search' => ['except' => ''],
        'selectedCategories' => ['except' => []],
        'selectedDepartments' => ['except' => []],
        'selectedStatuses' => ['except' => []],
        'selectedConditions' => ['except' => []],
        'priceRange' => ['except' => [0, 100000]],
        'sortBy' => ['except' => 'newest'],
        'view' => ['except' => 'grid']
    ];

    public function mount(): void
    {
        $this->categories = AssetCategory::all();
        $this->departments = Department::all();
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function clearFilters(): void
    {
        $this->reset([
            'search',
            'selectedCategories',
            'selectedDepartments',
            'selectedStatuses',
            'selectedConditions',
            'priceRange',
            'sortBy'
        ]);
    }

    public function toggleView(): void
    {
        $this->view = $this->view === 'grid' ? 'list' : 'grid';
    }

    public function render(): View
    {
        return view('livewire.assets.index-page', [
            'assets' => Asset::query()
                ->when($this->search, function ($query, $search) {
                    $query->where(function ($q) use ($search) {
                        $q->where('name', 'like', '%'.$search.'%')
                            ->orWhere('asset_code', 'like', '%'.$search.'%');
                    });
                })
                ->with(['category', 'department', 'assignedUser'])
                ->paginate(10),
        ]);
    }
}

<?php

namespace App\Livewire\Assets;

use App\Models\Asset;
use App\Models\AssetCategory;
use App\Models\Department;
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
    public $view = 'grid'; // grid or list

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

    public function getAssetsProperty()
    {
        return Asset::query()
            ->when($this->search, function ($query) {
                $query->where(function ($query) {
                    $query->where('name', 'like', '%' . $this->search . '%')
                        ->orWhere('asset_code', 'like', '%' . $this->search . '%')
                        ->orWhere('serial_number', 'like', '%' . $this->search . '%')
                        ->orWhere('barcode', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->selectedCategories, function ($query) {
                $query->whereIn('category_id', $this->selectedCategories);
            })
            ->when($this->selectedDepartments, function ($query) {
                $query->whereIn('current_department_id', $this->selectedDepartments);
            })
            ->when($this->selectedStatuses, function ($query) {
                $query->whereIn('status', $this->selectedStatuses);
            })
            ->when($this->selectedConditions, function ($query) {
                $query->whereIn('condition', $this->selectedConditions);
            })
            ->when($this->priceRange, function ($query) {
                $query->whereBetween('value', $this->priceRange);
            })
            ->when($this->sortBy, function ($query) {
                match ($this->sortBy) {
                    'newest' => $query->latest(),
                    'oldest' => $query->oldest(),
                    'name_asc' => $query->orderBy('name'),
                    'name_desc' => $query->orderBy('name', 'desc'),
                    'value_asc' => $query->orderBy('value'),
                    'value_desc' => $query->orderBy('value', 'desc'),
                    default => $query->latest()
                };
            })
            ->with(['category', 'department', 'assetImages'])
            ->paginate(12);
    }

    public function render(): View
    {
        return view('livewire.assets.index-page');
    }
}

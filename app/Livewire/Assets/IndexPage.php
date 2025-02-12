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
    public $maxPriceRange;

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
        $this->categories = AssetCategory::where('organization_id', auth()->user()->organization_id)->get();
        $this->departments = Department::where('organization_id', auth()->user()->organization_id)->get();
        $this->maxPriceRange = Asset::where('organization_id', auth()->user()->organization_id)->max('value');

        $this->priceRange = [0, $this->maxPriceRange];
    }

    public function updating($name): void
    {
        if (in_array($name, [
            'search', 'selectedCategories', 'selectedDepartments',
            'selectedStatuses', 'selectedConditions', 'priceRange'
        ])) {
            $this->resetPage();
        }
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
        $this->resetPage();
    }

    public function toggleView(): void
    {
        $this->view = $this->view === 'grid' ? 'list' : 'grid';
    }

    protected function getAssetQuery()
    {
        $query = Asset::query()
            ->where('organization_id', auth()->user()->organization_id)
            ->with(['category', 'department', 'assignedUser', 'assetImages']);

        // Apply search filter
        if ($this->search) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('asset_code', 'like', '%' . $this->search . '%')
                    ->orWhere('description', 'like', '%' . $this->search . '%')
                    ->orWhere('serial_number', 'like', '%' . $this->search . '%');
            });
        }

        // Apply category filter
        if (!empty($this->selectedCategories)) {
            $query->whereIn('category_id', $this->selectedCategories);
        }

        // Apply department filter
        if (!empty($this->selectedDepartments)) {
            $query->whereIn('current_department_id', $this->selectedDepartments);
        }

        // Apply status filter
        if (!empty($this->selectedStatuses)) {
            $query->whereIn('status', $this->selectedStatuses);
        }

        // Apply condition filter
        if (!empty($this->selectedConditions)) {
            $query->whereIn('condition', $this->selectedConditions);
        }

        // Apply price range filter
        if ($this->priceRange[0] > 0 || $this->priceRange[1] < 100000) {
            $query->whereBetween('value', $this->priceRange);
        }

        // Apply sorting
        switch ($this->sortBy) {
            case 'newest':
                $query->latest();
                break;
            case 'oldest':
                $query->oldest();
                break;
            case 'name_asc':
                $query->orderBy('name', 'asc');
                break;
            case 'name_desc':
                $query->orderBy('name', 'desc');
                break;
            case 'value_asc':
                $query->orderBy('value', 'asc');
                break;
            case 'value_desc':
                $query->orderBy('value', 'desc');
                break;
        }

        return $query;
    }

    public function render(): View
    {
        $assets = $this->getAssetQuery()->paginate(12);

        return view('livewire.assets.index-page', [
            'assets' => $assets,
        ]);
    }
}

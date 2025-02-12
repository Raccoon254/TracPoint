<?php

namespace App\Livewire\Categories;

use App\Models\AssetCategory;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\WithPagination;

class CategoriesIndex extends Component
{
    use WithPagination;

    public $search = '';
    public $sortField = 'name';
    public $sortDirection = 'asc';

    // Modal States
    public $showCreateModal = false;
    public $showEditModal = false;

    // Form Data
    public $categoryId = null;
    public $name = '';
    public $description = '';
    public $parent_id = null;
    public $depreciation_rate = 0;
    public $requires_maintenance = false;
    public $maintenance_frequency = null;

    // Validation Rules
    protected $rules = [
        'name' => 'required|min:2|max:255',
        'description' => 'nullable|max:1000',
        'parent_id' => 'nullable|exists:asset_categories,id',
        'depreciation_rate' => 'nullable|numeric|min:0|max:100',
        'requires_maintenance' => 'boolean',
        'maintenance_frequency' => 'nullable|integer|min:1',
    ];

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function sortBy($field): void
    {
        $this->sortDirection = $this->sortField === $field
            ? $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc'
            : 'asc';

        $this->sortField = $field;
    }

    public function createModal(): void
    {
        $this->reset(['showEditModal', 'categoryId', 'name', 'description', 'parent_id',
            'depreciation_rate', 'requires_maintenance', 'maintenance_frequency']);
        $this->showCreateModal = true;
    }

    public function createCategory(): void
    {
        $this->validate();

        AssetCategory::create([
            'name' => $this->name,
            'description' => $this->description,
            'parent_id' => $this->parent_id,
            'depreciation_rate' => $this->depreciation_rate,
            'requires_maintenance' => $this->requires_maintenance,
            'maintenance_frequency' => $this->maintenance_frequency,
            'organization_id' => auth()->user()->organization_id,
        ]);

        $this->reset(['showCreateModal', 'name', 'description', 'parent_id',
            'depreciation_rate', 'requires_maintenance', 'maintenance_frequency']);
        $this->dispatch('category-saved');
    }

    public function editCategory($categoryId): void
    {
        $category = AssetCategory::findOrFail($categoryId);
        $this->categoryId = $category->id;
        $this->name = $category->name;
        $this->description = $category->description;
        $this->parent_id = $category->parent_id;
        $this->depreciation_rate = $category->depreciation_rate;
        $this->requires_maintenance = $category->requires_maintenance;
        $this->maintenance_frequency = $category->maintenance_frequency;

        $this->showEditModal = true;
    }

    public function updateCategory(): void
    {
        $this->validate();

        $category = AssetCategory::findOrFail($this->categoryId);
        $category->update([
            'name' => $this->name,
            'description' => $this->description,
            'parent_id' => $this->parent_id,
            'depreciation_rate' => $this->depreciation_rate,
            'requires_maintenance' => $this->requires_maintenance,
            'maintenance_frequency' => $this->maintenance_frequency,
        ]);

        $this->reset(['showEditModal', 'categoryId', 'name', 'description', 'parent_id',
            'depreciation_rate', 'requires_maintenance', 'maintenance_frequency']);
        $this->dispatch('category-updated');
    }

    public function render(): View
    {
        $categories = AssetCategory::query()
            ->where('organization_id', auth()->user()->organization_id)
            ->when($this->search, function($query, $search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(10);

        $parentCategories = AssetCategory::where('organization_id', auth()->user()->organization_id)
            ->whereNull('parent_id')
            ->get();

        return view('livewire.categories.categories-index', [
            'categories' => $categories,
            'parentCategories' => $parentCategories,
        ]);
    }
}

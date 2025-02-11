<?php

namespace App\Livewire\Assets;

use App\Models\Asset;
use App\Models\AssetCategory;
use App\Models\Department;
use App\Models\User;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Str;

class Create extends Component
{
    use WithFileUploads;

    // Step tracking
    public int $step = 1;

    // Category fields - Step 1
    public $categoryMode = false; // false = select existing, true = create new
    public $category_id = '';
    public $category_name = '';
    public $category_description = '';
    public $parent_category_id = null;
    public $depreciation_rate = 0;
    public $requires_maintenance = false;
    public $maintenance_frequency = null;

    // Basic Asset Information - Step 2
    public $name = '';
    public $description = '';
    public $model = '';
    public $manufacturer = '';
    public $serial_number = '';
    public $barcode = '';
    public $is_mobile = false;

    // Financial Information - Step 3
    public $value = '';
    public $purchase_date = '';
    public $warranty_expiry = '';
    public $depreciation_rate_asset = 0;

    // Assignment Information - Step 4
    public $status = 'available';
    public $condition = 'new';
    public $current_department_id = null;
    public $current_location = '';
    public $assigned_to = null;
    public $assigned_date = null;
    public $expected_return_date = null;
    public $notes = '';

    // Asset Images - Step 5
    public $primary_image = null;
    public $additional_images = [];

    // Lists for dropdowns
    public $categories;
    public $departments;
    public $users;
    public $statusOptions = ['available', 'assigned', 'in_maintenance', 'retired'];
    public $conditionOptions = ['new', 'good', 'fair', 'poor'];

    public function mount(): void
    {
        $this->categories = AssetCategory::all();
        $this->departments = Department::all();
        $this->users = User::all();
    }

    public function updatedCategoryId($value): void
    {
        if ($value) {
            $category = AssetCategory::find($value);
            $this->depreciation_rate_asset = $category->depreciation_rate;
        }
    }

    public function createCategory(): void
    {
        $this->validate([
            'category_name' => 'required|string|max:255',
            'category_description' => 'nullable|string',
            'parent_category_id' => 'nullable|exists:asset_categories,id',
            'depreciation_rate' => 'required|numeric|min:0|max:100',
            'maintenance_frequency' => 'nullable|integer|min:0',
        ]);

        $category = AssetCategory::create([
            'name' => $this->category_name,
            'description' => $this->category_description,
            'parent_id' => $this->parent_category_id,
            'depreciation_rate' => $this->depreciation_rate,
            'requires_maintenance' => $this->requires_maintenance,
            'maintenance_frequency' => $this->maintenance_frequency,
        ]);

        $this->category_id = $category->id;
        $this->categories = AssetCategory::all();
        $this->categoryMode = false;
    }

    public function nextStep(): void
    {
        $this->validateStep();
        $this->step++;
    }

    public function previousStep(): void
    {
        $this->step--;
    }

    protected function validateStep(): void
    {
        match ($this->step) {
            1 => $this->validate([
                'category_id' => 'required|exists:asset_categories,id',
            ]),
            2 => $this->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'model' => 'nullable|string|max:255',
                'manufacturer' => 'nullable|string|max:255',
                'serial_number' => 'nullable|string|max:255',
                'barcode' => 'nullable|string|max:255|unique:assets,barcode',
            ]),
            3 => $this->validate([
                'value' => 'required|numeric|min:0',
                'purchase_date' => 'required|date',
                'warranty_expiry' => 'nullable|date|after:purchase_date',
                'depreciation_rate_asset' => 'required|numeric|min:0|max:100',
            ]),
            4 => $this->validate([
                'status' => 'required|in:' . implode(',', $this->statusOptions),
                'condition' => 'required|in:' . implode(',', $this->conditionOptions),
                'current_department_id' => 'required|exists:departments,id',
                'current_location' => 'required|string|max:255',
                'assigned_to' => 'nullable|exists:users,id',
                'assigned_date' => 'nullable|required_with:assigned_to|date',
                'expected_return_date' => 'nullable|date|after:assigned_date',
            ]),
            5 => $this->validate([
                'primary_image' => 'nullable|image|max:2048',
                'additional_images.*' => 'nullable|image|max:2048',
            ]),
            default => null,
        };
    }

    public function save(): void
    {
        $this->validateStep();

        // Generate asset code
        $assetCode = 'AST-' . strtoupper(Str::random(8));

        // Create the asset
        $asset = Asset::create([
            'asset_code' => $assetCode,
            'category_id' => $this->category_id,
            'name' => $this->name,
            'description' => $this->description,
            'model' => $this->model,
            'manufacturer' => $this->manufacturer,
            'serial_number' => $this->serial_number,
            'barcode' => $this->barcode,
            'is_mobile' => $this->is_mobile,
            'value' => $this->convertToDecimal($this->value),
            'purchase_date' => $this->purchase_date ?: null,
            'warranty_expiry' => $this->warranty_expiry ?: null,
            'depreciation_rate' => $this->depreciation_rate_asset,
            'status' => $this->status,
            'condition' => $this->condition,
            'current_department_id' => $this->current_department_id,
            'current_location' => $this->current_location,
            'assigned_to' => $this->assigned_to,
            'assigned_date' => $this->assigned_date,
            'expected_return_date' => $this->expected_return_date,
            'notes' => $this->notes,
        ]);

        // Handle image uploads
        if ($this->primary_image) {
            $primaryPath = $this->primary_image->store('assets', 'public');
            $asset->assetImages()->create([
                'image_path' => $primaryPath,
                'image_type' => 'primary',
            ]);
        }

        foreach ($this->additional_images as $image) {
            $path = $image->store('assets', 'public');
            $asset->assetImages()->create([
                'image_path' => $path,
                'image_type' => 'additional',
            ]);
        }

        session()->flash('message', 'Asset created successfully.');
        $this->redirect(route('assets.index'));
    }

    public function render(): View
    {
        return view('livewire.assets.create');
    }

    private function convertToDecimal(mixed $value): float
    {
        return number_format((float) $value, 2, '.', '');
    }
}

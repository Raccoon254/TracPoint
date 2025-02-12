<?php

namespace App\Livewire\Assets;

use App\Models\Asset;
use App\Models\AssetCategory;
use App\Models\Department;
use App\Models\User;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\WithFileUploads;

class EditAsset extends Component
{
    use WithFileUploads;

    public Asset $asset;

    // Step tracking
    public int $step = 1;

    // Category fields - Step 1
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
    public $existing_images = [];
    public $images_to_delete = [];

    // Lists for dropdowns
    public $categories;
    public $departments;
    public $users;
    public $statusOptions = ['available', 'assigned', 'in_maintenance', 'retired'];
    public $conditionOptions = ['new', 'good', 'fair', 'poor'];

    public $originalData = [];
    public $hasUnsavedChanges = false;

    public function mount(Asset $asset): void
    {
        $this->asset = $asset->load(['assetImages']);
        $this->categories = AssetCategory::all();
        $this->departments = Department::all();
        $this->users = User::all();

        // Load existing data
        $this->category_id = $asset->category_id;
        $this->name = $asset->name;
        $this->description = $asset->description;
        $this->model = $asset->model;
        $this->manufacturer = $asset->manufacturer;
        $this->serial_number = $asset->serial_number;
        $this->barcode = $asset->barcode;
        $this->is_mobile = $asset->is_mobile;
        $this->value = $asset->value;
        $this->purchase_date = $asset->purchase_date?->format('Y-m-d');
        $this->warranty_expiry = $asset->warranty_expiry?->format('Y-m-d');
        $this->depreciation_rate_asset = $asset->depreciation_rate;
        $this->status = $asset->status;
        $this->condition = $asset->condition;
        $this->current_department_id = $asset->current_department_id;
        $this->current_location = $asset->current_location;
        $this->assigned_to = $asset->assigned_to;
        $this->assigned_date = $asset->assigned_date?->format('Y-m-d');
        $this->expected_return_date = $asset->expected_return_date?->format('Y-m-d');
        $this->notes = $asset->notes;

        // Load existing images
        $this->existing_images = $asset->assetImages->toArray();

        // Store original data for comparison
        $this->originalData = [
            'category_id' => $asset->category_id,
            'name' => $asset->name,
            'description' => $asset->description,
            'model' => $asset->model,
            'manufacturer' => $asset->manufacturer,
            'serial_number' => $asset->serial_number,
            'barcode' => $asset->barcode,
            'is_mobile' => $asset->is_mobile,
            'value' => $asset->value,
            'purchase_date' => $asset->purchase_date?->format('Y-m-d'),
            'warranty_expiry' => $asset->warranty_expiry?->format('Y-m-d'),
            'depreciation_rate_asset' => $asset->depreciation_rate,
            'status' => $asset->status,
            'condition' => $asset->condition,
            'current_department_id' => $asset->current_department_id,
            'current_location' => $asset->current_location,
            'assigned_to' => $asset->assigned_to,
            'assigned_date' => $asset->assigned_date?->format('Y-m-d'),
            'expected_return_date' => $asset->expected_return_date?->format('Y-m-d'),
            'notes' => $asset->notes,
        ];

        // Check for unsaved changes in session
        if (session()->has('unsaved_changes_' . $asset->id)) {
            $this->hasUnsavedChanges = true;

            // Restore unsaved changes from session
            $unsavedData = session('unsaved_changes_' . $asset->id);
            foreach ($unsavedData as $key => $value) {
                if (property_exists($this, $key)) {
                    $this->$key = $value;
                }
            }
        }
    }

    public function updatedCategoryId($value): void
    {
        if ($value) {
            $category = AssetCategory::find($value);
            $this->depreciation_rate_asset = $category->depreciation_rate;
        }
    }

    public function updated($field): void
    {
        // Check for changes whenever a field is updated
        $this->checkForChanges();

        // Store current state in session
        $this->storeCurrentState();
    }

    protected function checkForChanges(): void
    {
        $currentData = [
            'category_id' => $this->category_id,
            'name' => $this->name,
            'description' => $this->description,
            'model' => $this->model,
            'manufacturer' => $this->manufacturer,
            'serial_number' => $this->serial_number,
            'barcode' => $this->barcode,
            'is_mobile' => $this->is_mobile,
            'value' => $this->value,
            'purchase_date' => $this->purchase_date,
            'warranty_expiry' => $this->warranty_expiry,
            'depreciation_rate_asset' => $this->depreciation_rate_asset,
            'status' => $this->status,
            'condition' => $this->condition,
            'current_department_id' => $this->current_department_id,
            'current_location' => $this->current_location,
            'assigned_to' => $this->assigned_to,
            'assigned_date' => $this->assigned_date,
            'expected_return_date' => $this->expected_return_date,
            'notes' => $this->notes,
        ];

        $this->hasUnsavedChanges = $currentData !== $this->originalData ||
            !empty($this->images_to_delete) ||
            $this->primary_image !== null ||
            !empty($this->additional_images);
    }

    protected function storeCurrentState(): void
    {
        if ($this->hasUnsavedChanges) {
            $currentState = [
                'category_id' => $this->category_id,
                'name' => $this->name,
                'description' => $this->description,
                'model' => $this->model,
                'manufacturer' => $this->manufacturer,
                'serial_number' => $this->serial_number,
                'barcode' => $this->barcode,
                'is_mobile' => $this->is_mobile,
                'value' => $this->value,
                'purchase_date' => $this->purchase_date,
                'warranty_expiry' => $this->warranty_expiry,
                'depreciation_rate_asset' => $this->depreciation_rate_asset,
                'status' => $this->status,
                'condition' => $this->condition,
                'current_department_id' => $this->current_department_id,
                'current_location' => $this->current_location,
                'assigned_to' => $this->assigned_to,
                'assigned_date' => $this->assigned_date,
                'expected_return_date' => $this->expected_return_date,
                'notes' => $this->notes,
            ];

            session(['unsaved_changes_' . $this->asset->id => $currentState]);
        } else {
            session()->forget('unsaved_changes_' . $this->asset->id);
        }
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

    public function removeExistingImage($imageId): void
    {
        $this->images_to_delete[] = $imageId;
        $this->existing_images = array_filter($this->existing_images, function($image) use ($imageId) {
            return $image['id'] !== $imageId;
        });
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
                'barcode' => 'nullable|string|max:255|unique:assets,barcode,' . $this->asset->id,
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

        // Update the asset
        $this->asset->update([
            'category_id' => $this->category_id,
            'organization_id' => auth()->user()->organization_id,
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

        // Delete removed images
        if (!empty($this->images_to_delete)) {
            $this->asset->assetImages()
                ->whereIn('id', $this->images_to_delete)
                ->delete();
        }

        // Handle new image uploads
        if ($this->primary_image) {
            // Delete existing primary image if exists
            $this->asset->assetImages()
                ->where('image_type', 'primary')
                ->delete();

            $primaryPath = $this->primary_image->store('assets', 'public');
            $this->asset->assetImages()->create([
                'image_path' => $primaryPath,
                'image_type' => 'primary',
            ]);
        }

        foreach ($this->additional_images as $image) {
            $path = $image->store('assets', 'public');
            $this->asset->assetImages()->create([
                'image_path' => $path,
                'image_type' => 'additional',
            ]);
        }

        // Clear unsaved changes from session
        session()->forget('unsaved_changes_' . $this->asset->id);

        session()->flash('message', 'Asset updated successfully.');
        $this->redirect(route('assets.show', $this->asset));
    }

    public function render(): View
    {
        return view('livewire.assets.edit-asset');
    }

    private function convertToDecimal(mixed $value): float
    {
        return number_format((float) $value, 2, '.', '');
    }
}

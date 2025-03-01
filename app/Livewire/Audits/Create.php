<?php

namespace App\Livewire\Audits;

use App\Models\Asset;
use App\Models\Audit;
use App\Models\Department;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Validate;

class Create extends Component
{
    use WithFileUploads;

    // Step tracking
    public $currentStep = 1;
    public $totalSteps = 4;
    public $stepTitles = [
        1 => 'Select Asset',
        2 => 'Verify Location',
        3 => 'Assess Condition',
        4 => 'Review & Submit'
    ];

    // Step 1: Asset Selection
    public $searchQuery = '';
    public $selectedAssetId = null;
    public $assetResults = [];
    public $departmentFilter = '';
    public $categoryFilter = '';
    public $departments = [];
    public $categories = [];

    // Step 2: Location Verification
    public $locationVerified = false;
    public $actualLocation = '';
    public $locationNotes = '';

    // Step 3: Condition Assessment
    public $previousCondition = '';
    public $newCondition = '';
    public $conditionOptions = ['new', 'good', 'fair', 'poor'];
    public $discrepancies = '';
    public $images = [];

    // Step 4: Review & Submit
    public $actionTaken = '';
    public $additionalNotes = '';

    // Selected Asset
    public $selectedAsset = null;

    protected function rules()
    {
        return [
            'selectedAssetId' => 'required|exists:assets,id',
            'locationVerified' => 'required|boolean',
            'actualLocation' => 'required_if:locationVerified,false|string|max:255',
            'locationNotes' => 'nullable|string|max:500',
            'newCondition' => 'required|in:new,good,fair,poor',
            'discrepancies' => 'nullable|string|max:1000',
            'images.*' => 'nullable|image|max:5120', // 5MB max
            'actionTaken' => 'nullable|string|max:500',
            'additionalNotes' => 'nullable|string|max:1000',
        ];
    }

    protected $messages = [
        'selectedAssetId.required' => 'Please select an asset to audit.',
        'actualLocation.required_if' => 'Please provide the actual location of the asset.',
        'newCondition.required' => 'Please assess the condition of the asset.',
        'newCondition.in' => 'Condition must be one of: new, good, fair, poor.',
        'images.*.image' => 'The file must be an image.',
        'images.*.max' => 'Image size must not exceed 5MB.',
    ];

    // Computed property for asset search results
    #[Computed]
    public function searchResults()
    {
        if (strlen($this->searchQuery) < 2) {
            return [];
        }

        return Asset::where(function ($query) {
            $query->where('name', 'like', '%' . $this->searchQuery . '%')
                ->orWhere('asset_code', 'like', '%' . $this->searchQuery . '%')
                ->orWhere('serial_number', 'like', '%' . $this->searchQuery . '%');
        })
            ->when($this->departmentFilter, function ($query, $departmentId) {
                $query->where('current_department_id', $departmentId);
            })
            ->when($this->categoryFilter, function ($query, $categoryId) {
                $query->where('category_id', $categoryId);
            })
            ->where('organization_id', auth()->user()->organization_id)
            ->with(['category', 'department'])
            ->take(10)
            ->get();
    }

    // Computed property for recent audits by the current auditor
    #[Computed]
    public function recentAudits()
    {
        return Audit::where('auditor_id', auth()->id())
            ->with(['asset', 'asset.department'])
            ->latest('audit_date')
            ->take(5)
            ->get();
    }

    // Computed property for assets due for audit
    #[Computed]
    public function assetsDueForAudit()
    {
        return Asset::whereDoesntHave('audits', function($query) {
            $query->where('audit_date', '>', now()->subMonths(3));
        })
            ->where('organization_id', auth()->user()->organization_id)
            ->with(['category', 'department'])
            ->take(5)
            ->get();
    }

    public function mount(): void
    {
        // Load departments and categories for filtering
        $this->departments = Department::where('organization_id', auth()->user()->organization_id)
            ->orderBy('name')
            ->get();

        $this->categories = Asset::where('organization_id', auth()->user()->organization_id)
            ->select('category_id')
            ->with('category')
            ->distinct()
            ->get()
            ->pluck('category');
    }

    public function updatedSearchQuery()
    {
        $this->assetResults = $this->searchResults;
    }

    public function selectAsset($assetId): void
    {
        $this->selectedAssetId = $assetId;
        $this->selectedAsset = Asset::with(['category', 'department', 'assignedUser', 'audits' => function($query) {
            $query->latest('audit_date')->first();
        }])->findOrFail($assetId);

        // Pre-fill previous condition from the last audit or current asset condition
        if ($this->selectedAsset->audits->isNotEmpty()) {
            $this->previousCondition = $this->selectedAsset->audits->first()->new_condition;
        } else {
            $this->previousCondition = $this->selectedAsset->condition;
        }

        // Pre-fill new condition with the same value
        $this->newCondition = $this->previousCondition;

        // Pre-fill actual location
        $this->actualLocation = $this->selectedAsset->current_location;
    }

    public function nextStep(): void
    {
        if ($this->currentStep === 1) {
            $this->validate([
                'selectedAssetId' => 'required|exists:assets,id',
            ]);
        } elseif ($this->currentStep === 2) {
            $this->validate([
                'locationVerified' => 'required|boolean',
                'actualLocation' => 'required_if:locationVerified,false|string|max:255',
                'locationNotes' => 'nullable|string|max:500',
            ]);
        } elseif ($this->currentStep === 3) {
            $this->validate([
                'newCondition' => 'required|in:new,good,fair,poor',
                'discrepancies' => 'nullable|string|max:1000',
                'images.*' => 'nullable|image|max:5120',
            ]);
        }

        if ($this->currentStep < $this->totalSteps) {
            $this->currentStep++;
        }
    }

    public function previousStep(): void
    {
        if ($this->currentStep > 1) {
            $this->currentStep--;
        }
    }

    public function createAudit()
    {
        $this->validate();

        DB::transaction(function () {
            $imagePaths = [];

            // Process images if any were uploaded
            if (!empty($this->images)) {
                foreach ($this->images as $image) {
                    $path = $image->store('audit-images', 'public');
                    $imagePaths[] = $path;
                }
            }

            $audit = Audit::create([
                'asset_id' => $this->selectedAssetId,
                'auditor_id' => auth()->id(),
                'audit_date' => now(),
                'previous_condition' => $this->previousCondition,
                'new_condition' => $this->newCondition,
                'location_verified' => $this->locationVerified,
                'notes' => $this->additionalNotes,
                'images' => $imagePaths,
                'discrepancies' => $this->discrepancies,
                'action_taken' => $this->actionTaken,
            ]);

            // Update asset condition if it has changed
            if ($this->previousCondition !== $this->newCondition) {
                $this->selectedAsset->update([
                    'condition' => $this->newCondition,
                ]);
            }

            // Update asset location if it has changed
            if (!$this->locationVerified && $this->actualLocation !== $this->selectedAsset->current_location) {
                $this->selectedAsset->update([
                    'current_location' => $this->actualLocation,
                ]);
            }

            // Create notifications

            // Notify the asset owner if assigned
            if ($this->selectedAsset->assigned_to) {
                Notification::create(
                    type: 'asset_audited',
                    notifiable: $this->selectedAsset->assignedUser,
                    message: "Your asset {$this->selectedAsset->name} has been audited.",
                    data: [
                        'asset_id' => $this->selectedAsset->id,
                        'asset_name' => $this->selectedAsset->name,
                        'asset_code' => $this->selectedAsset->asset_code,
                        'audit_id' => $audit->id,
                        'condition' => $this->newCondition,
                        'location_verified' => $this->locationVerified,
                        'has_discrepancies' => !empty($this->discrepancies),
                    ],
                    link: route('audits.show', $audit),
                    fromUser: auth()->user()
                );
            }

            // Notify department manager if exists
            if ($this->selectedAsset->department?->manager_id) {
                Notification::create(
                    type: 'asset_audited_manager',
                    notifiable: $this->selectedAsset->department->manager,
                    message: "Asset {$this->selectedAsset->name} in your department has been audited.",
                    data: [
                        'asset_id' => $this->selectedAsset->id,
                        'asset_name' => $this->selectedAsset->name,
                        'asset_code' => $this->selectedAsset->asset_code,
                        'audit_id' => $audit->id,
                        'condition' => $this->newCondition,
                        'location_verified' => $this->locationVerified,
                        'has_discrepancies' => !empty($this->discrepancies),
                        'department' => $this->selectedAsset->department->name,
                    ],
                    link: route('audits.show', $audit),
                    fromUser: auth()->user()
                );
            }

            // If there are discrepancies, notify admins
            if (!empty($this->discrepancies)) {
                $admins = User::where('organization_id', $this->selectedAsset->organization_id)
                    ->whereIn('role', ['admin', 'super_admin'])
                    ->where('id', '!=', auth()->id())
                    ->when($this->selectedAsset->department?->manager_id, function($query) {
                        // Exclude department manager as they've already been notified
                        return $query->where('id', '!=', $this->selectedAsset->department->manager_id);
                    })
                    ->get();

                foreach ($admins as $admin) {
                    Notification::create(
                        type: 'asset_audit_discrepancy',
                        notifiable: $admin,
                        message: "Discrepancies found during audit of {$this->selectedAsset->name}",
                        data: [
                            'asset_id' => $this->selectedAsset->id,
                            'asset_name' => $this->selectedAsset->name,
                            'asset_code' => $this->selectedAsset->asset_code,
                            'audit_id' => $audit->id,
                            'condition' => $this->newCondition,
                            'location_verified' => $this->locationVerified,
                            'department' => $this->selectedAsset->department?->name,
                        ],
                        link: route('audits.show', $audit),
                        fromUser: auth()->user()
                    );
                }
            }
        });

        // Dispatch event for notification
        $this->dispatch('audit-created');

        // Redirect to the audit details page
        return $this->redirect(route('audits.index'), navigate: true);
    }

    public function render(): View
    {
        return view('livewire.audits.create', [
            'searchResults' => $this->searchResults,
            'recentAudits' => $this->recentAudits,
            'assetsDueForAudit' => $this->assetsDueForAudit,
        ]);
    }
}

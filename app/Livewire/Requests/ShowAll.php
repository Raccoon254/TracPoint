<?php

namespace App\Livewire\Requests;

use App\Models\AssetRequest;
use App\Models\Asset;
use App\Models\AssetCategory;
use App\Models\Notification;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Audit;
use Carbon\Carbon;

class ShowAll extends Component
{
    use WithPagination;

    public $search = '';
    public $sortField = 'created_at';
    public $sortDirection = 'desc';
    public $selectedStatus = '';
    public $selectedPriority = '';
    public $selectedCategory = '';

    // Modal Properties
    public $showActionModal = false;
    public $currentRequest = null;
    public $selectedAsset = null;
    public $rejectionNote = '';

    public $showRejectionNote = false;
    public $currentStep = 1;

    protected $queryString = [
        'search' => ['except' => ''],
        'sortField' => ['except' => 'created_at'],
        'sortDirection' => ['except' => 'desc'],
        'selectedStatus' => ['except' => ''],
        'selectedPriority' => ['except' => ''],
        'selectedCategory' => ['except' => ''],
    ];

    protected $priorityOptions = [
        'low' => 'Low',
        'medium' => 'Medium',
        'high' => 'High'
    ];

    protected $statusOptions = [
        'pending' => 'Pending',
        'approved' => 'Approved',
        'rejected' => 'Rejected',
        'fulfilled' => 'Fulfilled'
    ];

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function sortBy($field): void
    {
        $this->sortDirection = $this->sortField === $field
            ? $this->sortDirection === 'asc' ? 'desc' : 'asc'
            : 'asc';
        $this->sortField = $field;
    }

    public function showRequest($requestId): void
    {
        $this->currentRequest = AssetRequest::with(['requester', 'category'])->findOrFail($requestId);
        $this->currentStep = 1;
        $status = $this->currentRequest->status;
        if ($status !== 'pending') {
            $this->currentStep = 2;
        }
        $this->showActionModal = true;
    }

    public function toggleRejectionNote(): void
    {
        $this->showRejectionNote = !$this->showRejectionNote;
    }

    public function fulfillRequest(): void
    {
        if (!$this->currentRequest || !$this->selectedAsset) {
            return;
        }

        $this->validate([
            'selectedAsset' => 'required|exists:assets,id'
        ]);

        // Start database transaction
        DB::transaction(function () {
            $asset = Asset::findOrFail($this->selectedAsset);
            $requester = $this->currentRequest->requester;

            // Create an audit record for this change
            Audit::create([
                'asset_id' => $asset->id,
                'auditor_id' => auth()->id(),
                'audit_date' => now(),
                'previous_condition' => $asset->condition,
                'new_condition' => $asset->condition,
                'location_verified' => true,
                'notes' => "Asset assigned through request #{$this->currentRequest->id}",
                'action_taken' => 'assignment'
            ]);

            // Update the asset
            $asset->update([
                'status' => 'assigned',
                'assigned_to' => $this->currentRequest->requester_id,
                'assigned_date' => now(),
                'expected_return_date' => $this->currentRequest->required_until,
                'current_department_id' => $this->currentRequest->requester->department_id,
                'notes' => $asset->notes . "\n" . now()->format('Y-m-d H:i:s') .
                    ": Assigned to {$this->currentRequest->requester->name} through request #{$this->currentRequest->id}"
            ]);

            // Update the request status
            $this->currentRequest->update([
                'status' => 'fulfilled',
                'asset_id' => $this->selectedAsset,
                'notes' => ($this->currentRequest->notes ? $this->currentRequest->notes . "\n" : '') .
                    now()->format('Y-m-d H:i:s') . ": Asset {$asset->asset_code} assigned"
            ]);

            // Notify requester
            Notification::create(
                type: 'request_fulfilled',
                notifiable: $requester,
                message: "Your request for {$asset->name} has been fulfilled.",
                data: [
                    'request_id' => $this->currentRequest->id,
                    'asset_id' => $asset->id,
                    'asset_name' => $asset->name,
                    'asset_code' => $asset->asset_code,
                    'assigned_date' => now()->format('Y-m-d'),
                    'expected_return_date' => $this->currentRequest->required_until,
                ],
                link: route('my-assets'),
                fromUser: auth()->user()
            );

            // Notify department manager
            if ($requester->department?->manager_id) {
                Notification::create(
                    type: 'manager_asset_assigned',
                    notifiable: $requester->department->manager,
                    message: "Asset {$asset->name} has been assigned to {$requester->name}",
                    data: [
                        'request_id' => $this->currentRequest->id,
                        'asset_id' => $asset->id,
                        'asset_name' => $asset->name,
                        'asset_code' => $asset->asset_code,
                        'user_id' => $requester->id,
                        'user_name' => $requester->name,
                        'department' => $requester->department->name,
                    ],
                    link: route('assets.show', $asset),
                    fromUser: auth()->user()
                );
            }
        });

        $this->showActionModal = false;
        $this->dispatch('request-updated', 'Request fulfilled and asset assigned successfully!');
    }

    public function approveRequest(): void
    {
        if (!$this->currentRequest) return;

        DB::transaction(function () {
            $this->currentRequest->update([
                'status' => 'approved',
                'approved_by' => auth()->id(),
                'approval_date' => now(),
                'notes' => ($this->currentRequest->notes ? $this->currentRequest->notes . "\n" : '') .
                    now()->format('Y-m-d H:i:s') . ": Request approved by " . auth()->user()->name
            ]);

            // Notify requester
            Notification::create(
                type: 'request_approved',
                notifiable: $this->currentRequest->requester,
                message: "Your request for {$this->currentRequest->category->name} has been approved.",
                data: [
                    'request_id' => $this->currentRequest->id,
                    'category_name' => $this->currentRequest->category->name,
                    'approved_by' => auth()->user()->name,
                    'approved_date' => now()->format('Y-m-d'),
                ],
                link: route('requests.show', $this->currentRequest),
                fromUser: auth()->user()
            );

            // Load available assets
            $this->loadAvailableAssets();

            // Move to next step
            $this->currentStep = 2;
        });

        $this->dispatch('request-updated', 'Request approved successfully! Please select an asset to assign.');
    }

    public function rejectRequest(): void
    {
        if (!$this->currentRequest) return;

        $this->validate([
            'rejectionNote' => 'required|min:10'
        ]);

        DB::transaction(function () {
            $this->currentRequest->update([
                'status' => 'rejected',
                'approved_by' => auth()->id(),
                'approval_date' => now(),
                'notes' => ($this->currentRequest->notes ? $this->currentRequest->notes . "\n" : '') .
                    now()->format('Y-m-d H:i:s') . ": Request rejected by " . auth()->user()->name .
                    "\nReason: " . $this->rejectionNote
            ]);

            // Notify requester
            Notification::create(
                type: 'request_rejected',
                notifiable: $this->currentRequest->requester,
                message: "Your request for {$this->currentRequest->category->name} has been rejected.",
                data: [
                    'request_id' => $this->currentRequest->id,
                    'category_name' => $this->currentRequest->category->name,
                    'rejected_by' => auth()->user()->name,
                    'rejected_date' => now()->format('Y-m-d'),
                    'rejection_reason' => $this->rejectionNote,
                ],
                link: route('requests.show', $this->currentRequest),
                fromUser: auth()->user()
            );

            // Notify department manager
            if ($this->currentRequest->requester->department?->manager_id) {
                Notification::create(
                    type: 'manager_request_rejected',
                    notifiable: $this->currentRequest->requester->department->manager,
                    message: "Asset request from {$this->currentRequest->requester->name} has been rejected.",
                    data: [
                        'request_id' => $this->currentRequest->id,
                        'category_name' => $this->currentRequest->category->name,
                        'user_id' => $this->currentRequest->requester->id,
                        'user_name' => $this->currentRequest->requester->name,
                        'rejected_by' => auth()->user()->name,
                        'rejected_date' => now()->format('Y-m-d'),
                        'rejection_reason' => $this->rejectionNote,
                        'department' => $this->currentRequest->requester->department->name,
                    ],
                    link: route('requests.show', $this->currentRequest),
                    fromUser: auth()->user()
                );
            }
        });

        $this->showActionModal = false;
        $this->dispatch('request-updated', 'Request rejected successfully!');
    }

    public function updatedCurrentRequest($value): void
    {
        if ($value && $value['status'] === 'approved') {
            $this->loadAvailableAssets();
        }
    }

    protected function loadAvailableAssets(): void
    {
        if ($this->currentRequest && $this->currentRequest->status === 'approved') {
            $this->availableAssets = Asset::where('category_id', $this->currentRequest->category_id)
                ->where('status', 'available')
                ->get();
        }
    }

    public function resetModal(): void
    {
        $this->reset(['showActionModal', 'currentRequest', 'selectedAsset', 'rejectionNote', 'currentStep']);
    }

    public function render(): View
    {
        $query = AssetRequest::query()
            ->when(!auth()->user()->role === 'super_admin', function($query) {
                $query->where('organization_id', auth()->user()->organization_id);
            })
            ->when($this->search, function($query) {
                $query->whereHas('requester', function($q) {
                    $q->where('name', 'like', "%{$this->search}%");
                })->orWhereHas('category', function($q) {
                    $q->where('name', 'like', "%{$this->search}%");
                });
            })
            ->when($this->selectedStatus, function($query) {
                $query->where('status', $this->selectedStatus);
            })
            ->when($this->selectedPriority, function($query) {
                $query->where('priority', $this->selectedPriority);
            })
            ->when($this->selectedCategory, function($query) {
                $query->where('category_id', $this->selectedCategory);
            })
            ->with(['requester', 'category', 'approver'])
            ->orderBy($this->sortField, $this->sortDirection);

        $categories = AssetCategory::where('organization_id', auth()->user()->organization_id)
            ->orderBy('name')
            ->get();

        $availableAssets = null;
        if ($this->currentRequest && $this->currentRequest->status === 'approved') {
            $availableAssets = Asset::where('category_id', $this->currentRequest->category_id)
                ->where('status', 'available')
                ->get();
        }

        return view('livewire.requests.show-all', [
            'requests' => $query->paginate(10),
            'categories' => $categories,
            'priorityOptions' => $this->priorityOptions,
            'statusOptions' => $this->statusOptions,
            'availableAssets' => $availableAssets,
        ]);
    }
}

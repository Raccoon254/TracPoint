<?php

namespace App\Livewire\Requests;

use App\Models\AssetRequest;
use App\Models\Asset;
use App\Models\AssetCategory;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\WithPagination;

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
        $this->showActionModal = true;
    }

    public function approveRequest(): void
    {
        if (!$this->currentRequest) return;

        $this->currentRequest->update([
            'status' => 'approved',
            'approved_by' => auth()->id(),
            'approval_date' => now(),
        ]);

        $this->showActionModal = false;
        $this->dispatch('request-updated', 'Request approved successfully!');
    }

    public function toggleRejectionNote(): void
    {
        $this->showRejectionNote = !$this->showRejectionNote;
    }

    public function rejectRequest(): void
    {
        if (!$this->currentRequest) return;

        $this->validate([
            'rejectionNote' => 'required|min:10'
        ]);

        $this->currentRequest->update([
            'status' => 'rejected',
            'approved_by' => auth()->id(),
            'approval_date' => now(),
            'notes' => $this->rejectionNote
        ]);

        $this->showActionModal = false;
        $this->dispatch('request-updated', 'Request rejected successfully!');
    }

    public function fulfillRequest(): void
    {
        if (!$this->currentRequest || !$this->selectedAsset) return;

        $this->validate([
            'selectedAsset' => 'required|exists:assets,id'
        ]);

        $this->currentRequest->update([
            'status' => 'fulfilled',
            'asset_id' => $this->selectedAsset,
        ]);

        // Update the asset status
        Asset::where('id', $this->selectedAsset)->update([
            'status' => 'assigned',
            'assigned_to' => $this->currentRequest->requester_id,
            'assigned_date' => now(),
        ]);

        $this->showActionModal = false;
        $this->dispatch('request-updated', 'Request fulfilled successfully!');
    }

    public function resetModal(): void
    {
        $this->reset(['showActionModal', 'currentRequest', 'selectedAsset', 'rejectionNote']);
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

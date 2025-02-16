<?php

namespace App\Livewire\Requests;

use App\Models\Asset;
use App\Models\AssetRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\WithPagination;

class ShowUserRequests extends Component
{
    use WithPagination;

    public $activeTab = 'assets';
    public $showReturnModal = false;
    public $selectedAsset = null;
    public $returnNotes = '';
    public $returnCondition = '';
    public $search = '';
    public $statusFilter = '';

    protected $rules = [
        'returnNotes' => 'required|min:10',
        'returnCondition' => 'required|in:good,fair,poor'
    ];

    public function getRequestsProperty()
    {
        return AssetRequest::where('requester_id', auth()->id())
            ->when($this->search, function($query) {
                $query->whereHas('category', function($q) {
                    $q->where('name', 'like', "%{$this->search}%");
                });
            })
            ->when($this->statusFilter, function($query) {
                $query->where('status', $this->statusFilter);
            })
            ->with(['category', 'approver', 'asset'])
            ->latest()
            ->paginate(10);
    }

    public function getAssignedAssetsProperty()
    {
        return Asset::where('assigned_to', auth()->id())
            ->where('status', 'assigned')
            ->when($this->search, function($query) {
                $query->where(function($q) {
                    $q->where('name', 'like', "%{$this->search}%")
                        ->orWhere('asset_code', 'like', "%{$this->search}%");
                });
            })
            ->with(['category', 'department'])
            ->latest('assigned_date')
            ->paginate(10);
    }

    public function initiateReturn($assetId)
    {
        $this->selectedAsset = Asset::findOrFail($assetId);
        $this->showReturnModal = true;
    }

    public function returnAsset()
    {
        $this->validate();

        if (!$this->selectedAsset) {
            return;
        }

        DB::transaction(function () {
            // Create an audit record for the return
            $this->selectedAsset->audits()->create([
                'auditor_id' => auth()->id(),
                'audit_date' => now(),
                'previous_condition' => $this->selectedAsset->condition,
                'new_condition' => $this->returnCondition,
                'location_verified' => true,
                'notes' => $this->returnNotes,
                'action_taken' => 'asset_return'
            ]);

            // Update the asset
            $this->selectedAsset->update([
                'status' => 'available',
                'condition' => $this->returnCondition,
                'assigned_to' => null,
                'assigned_date' => null,
                'expected_return_date' => null,
                'notes' => ($this->selectedAsset->notes ? $this->selectedAsset->notes . "\n" : '') .
                    now()->format('Y-m-d H:i:s') . ": Asset returned by " . auth()->user()->name .
                    "\nReturn Notes: " . $this->returnNotes
            ]);
        });

        $this->reset(['showReturnModal', 'selectedAsset', 'returnNotes', 'returnCondition']);
        $this->dispatch('asset-returned', 'Asset returned successfully!');
    }

    public function render(): View
    {
        return view('livewire.requests.show-user-requests', [
            'requests' => $this->requests,
            'assignedAssets' => $this->assignedAssets,
        ]);
    }
}

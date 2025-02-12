<?php

namespace App\Livewire\Assets;

use App\Models\AssetCategory;
use App\Models\AssetRequest;
use App\Models\Asset;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\Attributes\Computed;

class RequestAsset extends Component
{
    public Asset $asset;

    // Form Data
    public $purpose = '';
    public $quantity = 1;
    public $priority = 'medium';
    public $required_from;
    public $required_until;
    public $notes = '';

    // Form Rules
    protected $rules = [
        'purpose' => 'required|string|min:10',
        'quantity' => 'required|integer|min:1',
        'priority' => 'required|in:low,medium,high',
        'required_from' => 'required|date|after:today',
        'required_until' => 'required|date|after:required_from',
        'notes' => 'nullable|string|max:500',
    ];

    public function mount(Asset $asset): void
    {
        // Check if user can request this asset (same organization)
        if (auth()->user()->organization_id !== $asset->organization_id) {
            abort(403);
        }

        $this->asset = $asset;
        $this->required_from = now()->addDay()->format('Y-m-d');
        $this->required_until = now()->addDays(30)->format('Y-m-d');
    }

    #[Computed]
    public function pendingRequests()
    {
        return AssetRequest::where('requester_id', auth()->user()->id)
            ->where('asset_id', $this->asset->id)
            ->where('status', 'pending')
            ->with(['category'])
            ->get();
    }

    #[Computed]
    public function assignedAssets()
    {
        return Asset::where('assigned_to', auth()->user()->id)
            ->where('status', 'assigned')
            ->with(['category', 'department'])
            ->get();
    }

    #[Computed]
    public function recentRequests()
    {
        return AssetRequest::where('requester_id', auth()->user()->id)
            ->with(['category', 'approver'])
            ->latest()
            ->take(5)
            ->get();
    }

    public function submitRequest(): null
    {
        $this->validate();

        AssetRequest::create([
            'requester_id' => auth()->id(),
            'category_id' => $this->asset->category_id,
            'purpose' => $this->purpose,
            'quantity' => $this->quantity,
            'priority' => $this->priority,
            'required_from' => $this->required_from,
            'required_until' => $this->required_until,
            'notes' => $this->notes,
            'status' => 'pending',
            'organization_id' => auth()->user()->organization_id,
            'asset_id' => $this->asset->id,
        ]);

        $this->reset(['purpose', 'quantity', 'priority', 'notes']);
        $this->dispatch('request-submitted');

        // Optionally redirect back to asset details
        return $this->redirect(route('assets.show', $this->asset), navigate: true);
    }

    public function cancelRequest($requestId): void
    {
        $request = AssetRequest::where('requester_id', auth()->id())
            ->where('id', $requestId)
            ->where('status', 'pending')
            ->firstOrFail();

        $request->update(['status' => 'cancelled']);
        $this->dispatch('request-cancelled');
    }

    public function render(): View
    {
        return view('livewire.assets.request-asset', [
            'pendingRequests' => $this->pendingRequests,
            'assignedAssets' => $this->assignedAssets,
            'recentRequests' => $this->recentRequests,
        ]);
    }
}

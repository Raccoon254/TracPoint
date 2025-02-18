<?php

namespace App\Livewire\Assets;

use App\Models\AssetCategory;
use App\Models\AssetRequest;
use App\Models\Asset;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Support\Facades\DB;
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

        DB::transaction(function () {
            $request = AssetRequest::create([
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

            // Create notification for the requester
            Notification::create(
                type: 'asset_requested',
                notifiable: auth()->user(),
                message: "Your request for {$this->asset->name} has been submitted and is pending approval.",
                data: [
                    'asset_id' => $this->asset->id,
                    'asset_name' => $this->asset->name,
                    'asset_code' => $this->asset->asset_code,
                    'request_id' => $request->id,
                    'priority' => $this->priority,
                    'required_from' => $this->required_from,
                    'required_until' => $this->required_until,
                ],
                link: route('requests.index'),
                fromUser: auth()->user()
            );

            // Notify department manager if exists
            if ($this->asset->department?->manager_id) {
                Notification::create(
                    type: 'manager_asset_request',
                    notifiable: $this->asset->department->manager,
                    message: auth()->user()->name . " has requested {$this->asset->name}",
                    data: [
                        'asset_id' => $this->asset->id,
                        'asset_name' => $this->asset->name,
                        'asset_code' => $this->asset->asset_code,
                        'request_id' => $request->id,
                        'requester_id' => auth()->id(),
                        'requester_name' => auth()->user()->name,
                        'priority' => $this->priority,
                        'required_from' => $this->required_from,
                        'required_until' => $this->required_until,
                        'department' => $this->asset->department->name,
                    ],
                    link: route('requests.show', $request),
                    fromUser: auth()->user()
                );
            }

            // Notify organization admins
            $admins = User::where('organization_id', $this->asset->organization_id)
                ->whereIn('role', ['admin', 'super_admin'])
                ->where('id', '!=', auth()->id())
                ->when($this->asset->department?->manager_id, function($query) {
                    // Exclude department manager as they've already been notified
                    return $query->where('id', '!=', $this->asset->department->manager_id);
                })
                ->get();

            foreach ($admins as $admin) {
                Notification::create(
                    type: 'admin_asset_request',
                    notifiable: $admin,
                    message: auth()->user()->name . " has requested {$this->asset->name}",
                    data: [
                        'asset_id' => $this->asset->id,
                        'asset_name' => $this->asset->name,
                        'asset_code' => $this->asset->asset_code,
                        'request_id' => $request->id,
                        'requester_id' => auth()->id(),
                        'requester_name' => auth()->user()->name,
                        'priority' => $this->priority,
                        'required_from' => $this->required_from,
                        'required_until' => $this->required_until,
                        'department' => $this->asset->department?->name,
                    ],
                    link: route('requests.show', $request),
                    fromUser: auth()->user()
                );
            }
        });

        $this->reset(['purpose', 'quantity', 'priority', 'notes']);
        $this->dispatch('request-submitted');

        return $this->redirect(route('assets.show', $this->asset), navigate: true);
    }

    public function cancelRequest($requestId): void
    {
        DB::transaction(function () use ($requestId) {
            $request = AssetRequest::where('requester_id', auth()->id())
                ->where('id', $requestId)
                ->where('status', 'pending')
                ->firstOrFail();

            $request->update(['status' => 'cancelled']);

            // Notify requester of cancellation
            Notification::create(
                type: 'request_cancelled',
                notifiable: auth()->user(),
                message: "You have cancelled your request for {$this->asset->name}.",
                data: [
                    'asset_id' => $this->asset->id,
                    'asset_name' => $this->asset->name,
                    'asset_code' => $this->asset->asset_code,
                    'request_id' => $request->id,
                ],
                link: route('requests.index'),
                fromUser: auth()->user()
            );

            // Notify admins of cancellation
            $admins = User::where('organization_id', $this->asset->organization_id)
                ->whereIn('role', ['admin', 'super_admin'])
                ->where('id', '!=', auth()->id())
                ->get();

            foreach ($admins as $admin) {
                Notification::create(
                    type: 'admin_request_cancelled',
                    notifiable: $admin,
                    message: auth()->user()->name . " has cancelled their request for {$this->asset->name}",
                    data: [
                        'asset_id' => $this->asset->id,
                        'asset_name' => $this->asset->name,
                        'asset_code' => $this->asset->asset_code,
                        'request_id' => $request->id,
                        'requester_id' => auth()->id(),
                        'requester_name' => auth()->user()->name,
                    ],
                    link: route('requests.show', $request),
                    fromUser: auth()->user()
                );
            }
        });

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

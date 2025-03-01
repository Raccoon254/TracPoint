<?php

namespace App\Livewire\Audits;

use App\Models\Audit;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\Attributes\Computed;

class Show extends Component
{
    public Audit $audit;
    public $currentImageIndex = 0;
    public $isGalleryOpen = false;
    public $showHistoryModal = false;
    public $canEditAudit = false;

    public function mount(Audit $audit): void
    {
        $this->audit = $audit->load(['asset', 'asset.department', 'asset.category', 'asset.assignedUser', 'auditor']);

        // Check if the user has permission to edit the audit
        $user = auth()->user();
        $this->canEditAudit = $user->id === $audit->auditor_id || $user->hasAdminAccess();

        // Mark any notifications related to this audit as read
        if ($user) {
            Notification::where('notifiable_id', $user->id)
                ->where('notifiable_type', User::class)
                ->where('data->audit_id', $this->audit->id)
                ->whereNull('read_at')
                ->update(['read_at' => now()]);
        }
    }

    #[Computed]
    public function assetAuditHistory()
    {
        return Audit::where('asset_id', $this->audit->asset_id)
            ->where('id', '!=', $this->audit->id)
            ->with('auditor')
            ->orderBy('audit_date', 'desc')
            ->get();
    }

    #[Computed]
    public function nextScheduledAuditDate()
    {
        // Typically audits are scheduled every 3 months
        return $this->audit->audit_date->addMonths(3);
    }

    #[Computed]
    public function hasImages()
    {
        return !empty($this->audit->images) && count($this->audit->images) > 0;
    }

    public function openGallery($index = 0): void
    {
        if ($this->hasImages) {
            $this->currentImageIndex = $index;
            $this->isGalleryOpen = true;
        }
    }

    public function closeGallery(): void
    {
        $this->isGalleryOpen = false;
    }

    public function nextImage(): void
    {
        if ($this->hasImages) {
            $this->currentImageIndex = ($this->currentImageIndex + 1) % count($this->audit->images);
        }
    }

    public function previousImage(): void
    {
        if ($this->hasImages) {
            $this->currentImageIndex = ($this->currentImageIndex - 1 + count($this->audit->images)) % count($this->audit->images);
        }
    }

    public function toggleHistoryModal(): void
    {
        $this->showHistoryModal = !$this->showHistoryModal;
    }

    public function getImageUrl($path): string
    {
        return Storage::url($path);
    }

    public function downloadReport(): void
    {
        // In a real implementation, this would generate a PDF report
        // For now, we'll just send a notification that this feature is coming
        $this->dispatch('report-download-initiated');
    }

    public function scheduleFollowUpAudit(): void
    {
        // This would create a scheduled audit in a real implementation
        // For now, we'll just send a notification that this feature is coming
        $this->dispatch('follow-up-scheduled');
    }

    public function render(): View
    {
        return view('livewire.audits.show');
    }
}

<?php

namespace App\Livewire\Assets;

use App\Models\Asset;
use Illuminate\View\View;
use Livewire\Component;

class Show extends Component
{
    public Asset $asset;

    public function mount(Asset $asset): void
    {
        $this->asset = $asset->load([
            'category',
            'department',
            'assignedUser',
            'assetImages',
            'maintenanceRecords' => function ($query) {
                $query->latest()->take(5);
            },
            'audits' => function ($query) {
                $query->latest()->take(5);
            }
        ]);
    }

    public function render(): View
    {
        return view('livewire.assets.show');
    }
}

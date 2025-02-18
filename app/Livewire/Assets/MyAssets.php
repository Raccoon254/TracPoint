<?php

namespace App\Livewire\Assets;

use App\Models\Asset;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\WithPagination;

class MyAssets extends Component
{
    use WithPagination;

    public $activeTab = 'current';
    public $search = '';
    public $categoryFilter = '';
    public $conditionFilter = '';
    public $assetCategories = [];

    public function mount(): void
    {
        $this->assetCategories = auth()->user()->organization
            ->assetCategories()
            ->orderBy('name')
            ->pluck('name', 'id')
            ->toArray();
    }

    public function getCurrentAssetsProperty()
    {
        return $this->getAssetQuery()
            ->where('status', 'assigned')
            ->latest('assigned_date')
            ->paginate(10);
    }

    public function getPreviousAssetsProperty()
    {
        return $this->getAssetQuery()
            ->whereIn('status', ['available', 'retired'])
            ->whereHas('audits', function($query) {
                $query->where('action_taken', 'asset_return');
            })
            ->latest('updated_at')
            ->paginate(10);
    }

    protected function getAssetQuery()
    {
        return Asset::query()
            ->where(function($query) {
                $query->where('assigned_to', auth()->id())
                    ->orWhereHas('audits', function($q) {
                        $q->where('auditor_id', auth()->id());
                    });
            })
            ->when($this->search, function($query) {
                $query->where(function($q) {
                    $q->where('name', 'like', "%{$this->search}%")
                        ->orWhere('asset_code', 'like', "%{$this->search}%")
                        ->orWhere('serial_number', 'like', "%{$this->search}%");
                });
            })
            ->when($this->categoryFilter, function($query) {
                $query->where('category_id', $this->categoryFilter);
            })
            ->when($this->conditionFilter, function($query) {
                $query->where('condition', $this->conditionFilter);
            })
            ->with(['category', 'department', 'maintenanceRecords', 'assetImages']);
    }

    public function getAssetStatsProperty()
    {
        return [
            'total_current' => Asset::where('assigned_to', auth()->id())
                ->where('status', 'assigned')
                ->count(),
            'total_value' => Asset::where('assigned_to', auth()->id())
                ->where('status', 'assigned')
                ->sum('value'),
            'pending_returns' => Asset::where('assigned_to', auth()->id())
                ->where('status', 'assigned')
                ->whereNotNull('expected_return_date')
                ->where('expected_return_date', '<=', now()->addDays(7))
                ->count(),
            'overdue_returns' => Asset::where('assigned_to', auth()->id())
                ->where('status', 'assigned')
                ->whereNotNull('expected_return_date')
                ->where('expected_return_date', '<', now())
                ->count(),
        ];
    }

    public function render(): View
    {
        return view('livewire.assets.my-assets', [
            'currentAssets' => $this->currentAssets,
            'previousAssets' => $this->previousAssets,
            'assetStats' => $this->assetStats,
        ]);
    }
}

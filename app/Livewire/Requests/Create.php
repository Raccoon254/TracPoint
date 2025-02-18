<?php

namespace App\Livewire\Requests;

use App\Models\Asset;
use App\Models\AssetCategory;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\WithPagination;

class Create extends Component
{
    use WithPagination;

    public string $search = '';
    public ?int $categoryFilter = null;
    public ?string $statusFilter = 'available';

    protected $queryString = [
        'search' => ['except' => ''],
        'categoryFilter' => ['except' => ''],
        'statusFilter' => ['except' => 'available'],
    ];

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function render(): View
    {
        $query = Asset::query()
            ->with(['category', 'department', 'assetImages'])
            ->where('organization_id', auth()->user()->organization_id);

        if ($this->search) {
            $query->where(function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('asset_code', 'like', '%' . $this->search . '%')
                    ->orWhere('description', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->categoryFilter) {
            $query->where('category_id', $this->categoryFilter);
        }

        if ($this->statusFilter) {
            $query->where('status', $this->statusFilter);
        }

        return view('livewire.requests.create', [
            'assets' => $query->paginate(10),
            'categories' => AssetCategory::where('organization_id', auth()->user()->organization_id)
                ->pluck('name', 'id'),
        ]);
    }
}

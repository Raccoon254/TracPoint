<?php

namespace App\Livewire\Dashboards;

use App\Models\Asset;
use App\Models\Audit;
use App\Models\User;
use Illuminate\View\View;
use Livewire\Component;

class AuditorDashboard extends Component
{
    public array $stats = [];
    public User $user;

    public function mount(): void
    {
        $this->user = auth()->user();
        $this->stats = $this->calculateStats();
    }

    public function calculateStats(): array
    {
        $user = $this->user;

        return [
            'completed_audits' => Audit::where('auditor_id', $user->id)->count(),
            'pending_audits' => Asset::whereDoesntHave('audits', function($query) {
                $query->where('audit_date', '>', now()->subMonths(3));
            })->count(),
            'recent_audits' => Audit::with('asset')
                ->where('auditor_id', $user->id)
                ->latest()
                ->take(5)
                ->get(),
        ];
    }

    public function render(): View
    {
        return view('livewire.dashboards.auditor-dashboard');
    }
}

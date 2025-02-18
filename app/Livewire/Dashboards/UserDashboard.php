<?php

namespace App\Livewire\Dashboards;

use Illuminate\View\View;
use Livewire\Component;

class UserDashboard extends Component
{
    public function render(): View
    {
        return view('livewire.dashboards.user-dashboard');
    }
}

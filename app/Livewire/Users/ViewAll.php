<?php

namespace App\Livewire\Users;

use Illuminate\View\View;
use Livewire\Component;

class ViewAll extends Component
{
    public function render(): View
    {
        return view('livewire.users.view-all');
    }
}

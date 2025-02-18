<?php

namespace App\Livewire\Requests;

use Illuminate\View\View;
use Livewire\Component;

class Show extends Component
{
    public function render(): View
    {
        return view('livewire.requests.show');
    }
}

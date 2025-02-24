<?php

namespace App\Livewire\Test;

use App\Models\User;
use Illuminate\View\View;
use Livewire\Component;

class Test extends Component
{
    public string $search = '';

    public function render(): View
    {
        return view('livewire.test.test', [
            'users' => User::where('name', 'like', "%{$this->search}%")->get(),
        ]);
    }
}

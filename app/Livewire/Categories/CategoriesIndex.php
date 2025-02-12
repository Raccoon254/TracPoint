<?php

namespace App\Livewire\Categories;

use Illuminate\View\View;
use Livewire\Component;

class CategoriesIndex extends Component
{
    public function render(): View
    {
        return view('livewire.categories.categories-index');
    }
}

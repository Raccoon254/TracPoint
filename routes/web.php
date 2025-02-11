<?php

use App\Livewire\Dashboards\MainDashboard;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome');

Route::get('dashboard', MainDashboard::class)
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

require __DIR__.'/auth.php';

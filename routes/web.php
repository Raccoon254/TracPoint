<?php

use App\Livewire\Assets\Create;
use App\Livewire\Dashboards\MainDashboard;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome');

Route::get('dashboard', MainDashboard::class)
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

//Assets - assets.create
Route::get('assets/create', Create::class)
    ->middleware(['auth', 'verified'])
    ->name('assets.create');

require __DIR__.'/auth.php';

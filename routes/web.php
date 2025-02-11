<?php

use App\Livewire\Assets\Create;
use App\Livewire\Assets\EditAsset;
use App\Livewire\Assets\Show;
use App\Livewire\Assets\ViewAll;
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

Route::get('assets/all', ViewAll::class)
    ->middleware(['auth', 'verified'])
    ->name('assets.index');

Route::get('assets/{asset}', Show::class)
    ->middleware(['auth', 'verified'])
    ->name('assets.show');

Route::get('assets/{asset}/edit', EditAsset::class)
    ->middleware(['auth', 'verified'])
    ->name('assets.edit');

require __DIR__.'/auth.php';

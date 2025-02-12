<?php

use App\Livewire\Assets\Create;
use App\Livewire\Assets\EditAsset;
use App\Livewire\Assets\IndexPage;
use App\Livewire\Assets\RequestAsset;
use App\Livewire\Assets\Show;
use App\Livewire\Assets\ViewAll;
use App\Livewire\Categories\CategoriesIndex;
use App\Livewire\Dashboards\MainDashboard;
use App\Livewire\Users\ShowUser;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', MainDashboard::class)->name('dashboard');

    Route::get('assets/create', Create::class)->name('assets.create');
    Route::get('assets/all', ViewAll::class)->name('assets.index');
    Route::get('assets/{asset}', Show::class)->name('assets.show');
    Route::get('assets/{asset}/edit', EditAsset::class)->name('assets.edit');
    Route::get('assets', IndexPage::class)->name('assets.browse');
    Route::get('assets/request/{asset}', RequestAsset::class)->name('assets.request');

    Route::get('categories', CategoriesIndex::class)->name('categories.index');

    Route::get('users', \App\Livewire\Users\ViewAll::class)->name('users.index');
    Route::get('users/{user}', ShowUser::class)->name('users.show');
});

require __DIR__.'/auth.php';

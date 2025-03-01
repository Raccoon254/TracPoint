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
    Route::get('my-assets', \App\Livewire\Assets\MyAssets::class)->name('my-assets');
    Route::get('assets', IndexPage::class)->name('assets.browse');
    Route::get('assets/request/{asset}', RequestAsset::class)->name('assets.request');
    Route::get('requests', \App\Livewire\Requests\ShowAll::class)->name('requests.index');
    Route::get('user/requests', \App\Livewire\Requests\ShowUserRequests::class)->name('user.requests');
    Route::get('requests/create', \App\Livewire\Requests\Create::class)->name('requests.create');
    Route::get('requests/{request}', \App\Livewire\Requests\Show::class)->name('requests.show');

    Route::get('categories', CategoriesIndex::class)->name('categories.index');

    Route::get('users', \App\Livewire\Users\ViewAll::class)->name('users.index');
    Route::get('users/{user}', ShowUser::class)->name('users.show');

    Route::get('maintenance', \App\Livewire\maintenance\Index::class)->name('maintenance.index');
    Route::get('departments', \App\Livewire\departments\Index::class)->name('departments.index');
    Route::get('audits', \App\Livewire\audits\Index::class)->name('audits.index');
    Route::get('reports', \App\Livewire\reports\Index::class)->name('reports.index');
    Route::get('settings', \App\Livewire\settings\Index::class)->name('settings.index');

    Route::get('audits/create', \App\Livewire\audits\Create::class)->name('audits.create');

    // Notifications
    Route::get('notifications', \App\Livewire\Notifications\ShowAll::class)->name('notifications.index');
});

require __DIR__.'/auth.php';

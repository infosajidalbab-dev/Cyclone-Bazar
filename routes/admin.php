<?php

use App\Livewire\Admin\BannerManager;
use App\Livewire\Admin\Dashboard;
use App\Livewire\Admin\OrderManager;
use App\Livewire\Admin\ProductForm;
use App\Livewire\Admin\SettingsManager;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Admin Panel Routes (Middleware: auth, admin)
|--------------------------------------------------------------------------
*/

Route::prefix('admin')->name('admin.')->group(function () {
    // Admin Dashboard (Section 9.1)
    Route::get('/dashboard', Dashboard::class)->name('dashboard');

    // Products Management
    Route::get('/products/create', ProductForm::class)->name('products.create');
    Route::get('/products/{id}/edit', ProductForm::class)->name('products.edit');

    // Orders Management & Courier Assignment
    Route::get('/orders', OrderManager::class)->name('orders.index');

    // Promotional Banners (Hero / Middle / Footer)
    Route::get('/banners', BannerManager::class)->name('banners.index');

    // General Store Settings (Logistics, COD, Pricing)
    Route::get('/settings', SettingsManager::class)->name('settings.index');
});

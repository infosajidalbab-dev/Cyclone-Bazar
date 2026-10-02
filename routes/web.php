<?php

use App\Livewire\Frontend\Cart;
use App\Livewire\Frontend\Checkout;
use App\Livewire\Frontend\OrderTracker;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes (Cyclone Mart V1 - Bangladesh Market)
|--------------------------------------------------------------------------
*/

// Storefront Homepage
Route::get('/', function () {
    return view('frontend.home');
})->name('home');

// Product Detail Page (Section 7.3)
Route::get('/product/{slug}', function (string $slug) {
    $product = Product::with(['category', 'images', 'variants', 'supplier'])
        ->where('slug', $slug)
        ->where('is_active', true)
        ->firstOrFail();

    return view('frontend.product-detail', compact('product'));
})->name('product.detail');

// Shopping Cart (Livewire 3)
Route::get('/cart', Cart::class)->name('cart');

// Single-Page Checkout (Section 7.5 - Livewire 3)
Route::get('/checkout', Checkout::class)->name('checkout');

// Order Confirmation
Route::get('/order-confirmation/{order_number}', function (string $orderNumber) {
    $order = Order::with(['items', 'shippingAddress'])
        ->where('order_number', $orderNumber)
        ->first();

    return view('frontend.order-confirmation', compact('order'));
})->name('order.confirmation');

// Guest Order Tracker (Order Number + Phone)
Route::get('/track', OrderTracker::class)->name('order.track');

// Fallback Redirect
Route::fallback(function () {
    return redirect()->route('home');
});

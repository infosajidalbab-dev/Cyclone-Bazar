<?php

namespace App\Livewire\Frontend;

use App\Models\Banner;
use App\Models\Category;
use App\Models\Product;
use App\Models\Setting;
use Livewire\Component;

class Home extends Component
{
    public function render()
    {
        $heroBanners = Banner::active()->position('hero')->get();
        $middleBanners = Banner::active()->position('middle')->get();
        $categories = Category::active()->take(8)->get();
        $featuredProducts = Product::with(['primaryImage', 'variants'])
            ->featured()
            ->take(8)
            ->get();

        return view('livewire.frontend.home', [
            'heroBanners' => $heroBanners,
            'middleBanners' => $middleBanners,
            'categories' => $categories,
            'featuredProducts' => $featuredProducts,
            'storeName' => Setting::get('store_name', 'Cyclone Mart'),
            'contactPhone' => Setting::get('contact_phone', '01712345678'),
        ]);
    }
}

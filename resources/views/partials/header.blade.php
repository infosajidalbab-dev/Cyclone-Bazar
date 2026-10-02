{{--
    Main Navigation Header Partial
    Usage: @include('partials.header', ['cartCount' => $count, 'cartTotal' => $total])
--}}
<x-header 
    :cartCount="$cartCount ?? (session('cart_count', 0))" 
    :cartTotal="$cartTotal ?? (session('cart_total', 0))"
    :hotline="$hotline ?? '০১৭১২-৩৪৫৬৭৮'"
    :hotlineRaw="$hotlineRaw ?? '01712345678'"
/>

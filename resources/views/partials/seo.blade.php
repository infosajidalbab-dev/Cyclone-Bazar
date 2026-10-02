@props([
    'title' => 'Cyclone Mart — Bangladesh Mobile-First Dropshipping',
    'description' => 'ক্যাশ অন ডেলিভারিতে অরিজিনাল গ্যাজেট ও ইলেকট্রনিক্স অর্ডার করুন সারা বাংলাদেশে। ৭ দিনের রিপ্লেসমেন্ট গ্যারান্টি।',
    'image' => asset('images/og-cyclone-mart.jpg'),
    'type' => 'website',
    'url' => url()->current(),
])

<!-- Primary Meta Tags -->
<title>{{ $title }}</title>
<meta name="title" content="{{ $title }}">
<meta name="description" content="{{ $description }}">

<!-- Open Graph / Facebook -->
<meta property="og:type" content="{{ $type }}">
<meta property="og:url" content="{{ $url }}">
<meta property="og:title" content="{{ $title }}">
<meta property="og:description" content="{{ $description }}">
<meta property="og:image" content="{{ $image }}">
<meta property="og:locale" content="bn_BD">
<meta property="og:site_name" content="Cyclone Mart">

<!-- Twitter -->
<meta property="twitter:card" content="summary_large_image">
<meta property="twitter:url" content="{{ $url }}">
<meta property="twitter:title" content="{{ $title }}">
<meta property="twitter:description" content="{{ $description }}">
<meta property="twitter:image" content="{{ $image }}">

<!-- Schema.org JSON-LD Structured Data for Bangladesh E-Commerce Store -->
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "OnlineStore",
  "name": "Cyclone Mart",
  "url": "{{ url('/') }}",
  "logo": "{{ asset('images/logo.png') }}",
  "description": "Mobile-first dropshipping e-commerce platform for the Bangladesh market featuring Cash on Delivery.",
  "address": {
    "@type": "PostalAddress",
    "addressLocality": "Dhaka",
    "addressCountry": "BD"
  },
  "potentialAction": {
    "@type": "SearchAction",
    "target": "{{ url('/search?q={search_term_string}') }}",
    "query-input": "required name=search_term_string"
  }
}
</script>

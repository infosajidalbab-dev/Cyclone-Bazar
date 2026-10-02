<!DOCTYPE html>
<html lang="bn-BD" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>অর্ডার সম্পন্ন হয়েছে — Cyclone Mart</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', system-ui, sans-serif; }
        .font-mono { font-family: 'JetBrains Mono', monospace; }
    </style>
</head>
<body class="text-slate-900 antialiased p-4 sm:p-8 flex items-center justify-center min-h-screen">
    <div class="max-w-md w-full bg-white rounded-3xl border border-slate-200 p-6 sm:p-8 shadow-sm space-y-6 text-center">
        {{-- Success Checkmark --}}
        <div class="w-16 h-16 bg-emerald-50 text-[#28A745] rounded-full flex items-center justify-center mx-auto border-4 border-emerald-100 shadow-xs">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
        </div>

        <div>
            <h1 class="text-xl sm:text-2xl font-bold tracking-tight text-slate-900">অর্ডার সফলভাবে সম্পন্ন হয়েছে!</h1>
            <p class="text-xs text-slate-500 mt-1">আপনার অর্ডারটি সিস্টেমে সংরক্ষিত হয়েছে। কোনো অগ্রিম ফি নেই।</p>
        </div>

        {{-- Order ID & Instructions Card --}}
        <div class="bg-slate-50 border border-slate-200 rounded-2xl p-4 text-left space-y-2.5 font-mono text-xs">
            <div class="flex justify-between items-center pb-2 border-b border-slate-200">
                <span class="text-slate-500">অর্ডার ট্র্যাকিং আইডি:</span>
                <span class="font-bold text-[#0F4C81] text-sm">{{ $order->order_number ?? request()->route('order_number') }}</span>
            </div>
            <div class="flex justify-between items-center">
                <span class="text-slate-500">পেমেন্ট মেথড:</span>
                <span class="font-bold text-slate-800">ক্যাশ অন ডেলিভারি (COD)</span>
            </div>
            <div class="flex justify-between items-center">
                <span class="text-slate-500">সর্বমোট প্রদেয়:</span>
                <span class="font-bold text-[#FF6B35]">৳{{ number_format($order->total_amount ?? 0, 2) }}</span>
            </div>
            <div class="flex justify-between items-center">
                <span class="text-slate-500">আনুমানিক ডেলিভারি:</span>
                <span class="text-slate-800">২-৩ কর্মদিবস</span>
            </div>
        </div>

        <div class="p-3 bg-blue-50 border border-blue-200 rounded-xl text-left text-xs text-[#0F4C81] space-y-1">
            <span class="font-bold block">পরবর্তী ধাপ (Next Steps):</span>
            <p class="text-[11px] text-blue-900">
                আমাদের কাস্টমার কেয়ার প্রতিনিধি ফোন কলের মাধ্যমে আপনার অর্ডার কনফার্ম করবেন। পার্সেল ডেলিভারির সময় রাইডারের কাছে নগদ মূল্য পরিশোধ করবেন।
            </p>
        </div>

        <div class="space-y-2 pt-2">
            <a href="/track" class="w-full min-h-[44px] bg-[#0F4C81] hover:bg-[#0A355C] text-white font-bold rounded-xl text-xs flex items-center justify-center gap-1.5 transition-colors shadow-xs">
                <span>অর্ডার ট্র্যাক করুন (Track Order)</span>
            </a>
            <a href="/" class="w-full min-h-[44px] bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold rounded-xl text-xs flex items-center justify-center transition-colors">
                <span>হোমপেজে ফিরে যান (Return Home)</span>
            </a>
        </div>
    </div>
</body>
</html>

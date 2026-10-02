<div class="max-w-6xl mx-auto px-4 py-6 sm:px-6 lg:px-8 space-y-6">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-4 border-b border-slate-200 gap-4">
        <div>
            <h1 class="text-xl sm:text-2xl font-bold tracking-tight text-slate-900">হোমপেজ ব্যানার ম্যানেজমেন্ট (Promotional Banners)</h1>
            <p class="text-xs text-slate-500 mt-0.5">হিরো স্লাইডার, মিডল অফার স্ট্রিপ এবং ফুটার প্রোমোশনাল ব্যানার কনফিগার করুন।</p>
        </div>

        <button 
            type="button" 
            wire:click="openCreateModal" 
            class="px-4 py-2 text-xs font-bold text-white bg-[#0F4C81] hover:bg-[#0A355C] rounded-xl shadow-xs min-h-[44px] flex items-center gap-1.5 transition-colors"
        >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            <span>নতুন ব্যানার যোগ করুন (+ New Banner)</span>
        </button>
    </div>

    {{-- Flash message --}}
    @if (session()->has('banner_status'))
        <div class="p-3 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl text-xs flex items-center gap-2">
            <svg class="w-4 h-4 text-[#28A745] shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
            <span>{{ session('banner_status') }}</span>
        </div>
    @endif

    {{-- Banners Table --}}
    <div class="bg-white border border-slate-200 rounded-3xl shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 border-b border-slate-200 text-slate-600 font-semibold">
                    <tr>
                        <th class="py-3 px-4">Preview</th>
                        <th class="py-3 px-4">Title & Subtitle</th>
                        <th class="py-3 px-4">Position</th>
                        <th class="py-3 px-4">Target URL</th>
                        <th class="py-3 px-4">Display Order</th>
                        <th class="py-3 px-4">Status</th>
                        <th class="py-3 px-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($banners as $b)
                        <tr class="hover:bg-slate-50/70 transition-colors">
                            <td class="py-3 px-4">
                                <div class="w-20 h-10 bg-slate-100 rounded-lg overflow-hidden border border-slate-200 flex items-center justify-center">
                                    @if ($b->image_path)
                                        <img src="{{ $b->image_path }}" class="w-full h-full object-cover" />
                                    @else
                                        <span class="text-[9px] font-mono text-slate-400">NO IMG</span>
                                    @endif
                                </div>
                            </td>
                            <td class="py-3 px-4">
                                <span class="font-bold text-slate-800 block">{{ $b->title }}</span>
                                <span class="text-[11px] text-slate-400">{{ $b->subtitle ?? 'No subtitle' }}</span>
                            </td>
                            <td class="py-3 px-4">
                                <span class="font-mono uppercase text-[10px] bg-blue-50 text-[#0F4C81] px-2 py-0.5 rounded font-bold">
                                    {{ $b->position }}
                                </span>
                            </td>
                            <td class="py-3 px-4 font-mono text-[11px] text-slate-500">
                                {{ $b->target_url ?? '/' }}
                            </td>
                            <td class="py-3 px-4 font-mono font-semibold text-slate-700">
                                {{ $b->display_order }}
                            </td>
                            <td class="py-3 px-4">
                                <button 
                                    type="button" 
                                    wire:click="toggleActive({{ $b->id }})"
                                    class="text-[10px] font-bold px-2 py-0.5 rounded-full {{ $b->is_active ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-200 text-slate-600' }}"
                                >
                                    {{ $b->is_active ? 'ACTIVE' : 'INACTIVE' }}
                                </button>
                            </td>
                            <td class="py-3 px-4 text-right space-x-2">
                                <button type="button" wire:click="openEditModal({{ $b->id }})" class="text-[#0F4C81] hover:underline font-semibold">Edit</button>
                                <button type="button" wire:click="deleteBanner({{ $b->id }})" onclick="return confirm('ব্যানারটি মুছে ফেলতে চান?')" class="text-rose-600 hover:underline">Delete</button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-8 text-center text-slate-400">
                                কোনো ব্যানার তৈরি করা হয়নি। নতুন ব্যানার যোগ করুন।
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Create / Edit Modal --}}
    @if ($isModalOpen)
        <div class="fixed inset-0 z-50 bg-black/60 backdrop-blur-xs flex items-center justify-center p-4">
            <div class="w-full max-w-lg bg-white rounded-3xl p-6 shadow-2xl space-y-4 max-h-[90vh] overflow-y-auto">
                <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                    <h3 class="text-sm font-bold text-slate-900">{{ $bannerId ? 'ব্যানার সম্পাদন (Edit Banner)' : 'নতুন ব্যানার (Add Banner)' }}</h3>
                    <button type="button" wire:click="closeModal" class="text-slate-400 hover:text-slate-600">✕</button>
                </div>

                <form wire:submit.prevent="save" class="space-y-3.5 text-xs">
                    <div>
                        <label class="block text-[11px] font-semibold text-slate-700 mb-1">ব্যানার শিরোনাম (Title) *</label>
                        <input type="text" wire:model="title" placeholder="e.g. ধামাকা অফার: সকল গ্যাজেটে ৪০% পর্যন্ত ছাড়!" class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs" />
                        @error('title') <span class="text-[10px] text-rose-500 mt-0.5 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-[11px] font-semibold text-slate-700 mb-1">সাবটাইটেল (Subtitle)</label>
                        <input type="text" wire:model="subtitle" placeholder="ক্যাশ অন ডেলিভারিতে সারা বাংলাদেশে দ্রুত ডেলিভারি" class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs" />
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-[11px] font-semibold text-slate-700 mb-1">পজিশন (Position) *</label>
                            <select wire:model="position" class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium">
                                <option value="hero">Hero Slider (হিরো স্লাইডার)</option>
                                <option value="middle">Middle Strip (মিডল স্ট্রিপ)</option>
                                <option value="footer">Footer Promo (ফুটার ব্যানার)</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-[11px] font-semibold text-slate-700 mb-1">ডিসপ্লে অর্ডার (Order)</label>
                            <input type="number" wire:model="display_order" class="w-full p-2.5 font-mono bg-slate-50 border border-slate-200 rounded-xl text-xs" />
                        </div>
                    </div>

                    <div>
                        <label class="block text-[11px] font-semibold text-slate-700 mb-1">টার্গেট লিঙ্ক (Target URL)</label>
                        <input type="text" wire:model="target_url" placeholder="/products/cyclone-tws-pro" class="w-full p-2.5 font-mono bg-slate-50 border border-slate-200 rounded-xl text-xs" />
                    </div>

                    <div>
                        <label class="block text-[11px] font-semibold text-slate-700 mb-1">ব্যানার ছবি (Banner Image) *</label>
                        <input type="file" wire:model="image" accept="image/*" class="w-full p-2 bg-slate-50 border border-slate-200 rounded-xl text-xs" />
                        @if ($existingImagePath && !$image)
                            <div class="mt-2 w-32 h-14 rounded-lg overflow-hidden border border-slate-200">
                                <img src="{{ $existingImagePath }}" class="w-full h-full object-cover" />
                            </div>
                        @endif
                        @error('image') <span class="text-[10px] text-rose-500 mt-0.5 block">{{ $message }}</span> @enderror
                    </div>

                    <label class="flex items-center gap-2 cursor-pointer pt-1 min-h-[44px]">
                        <input type="checkbox" wire:model="is_active" class="w-4 h-4 text-[#0F4C81] rounded border-slate-300" />
                        <span class="text-xs text-slate-700 font-semibold">ওয়েবসাইটে সক্রিয় রাখুন (Active)</span>
                    </label>

                    <div class="flex items-center justify-end gap-2 pt-2 border-t border-slate-100">
                        <button type="button" wire:click="closeModal" class="px-4 py-2 bg-slate-100 text-slate-600 rounded-xl">বাতিল</button>
                        <button type="submit" class="px-5 py-2 bg-[#0F4C81] text-white font-bold rounded-xl shadow-xs">সংরক্ষণ করুন</button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>

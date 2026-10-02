<div class="max-w-6xl mx-auto px-4 py-6 sm:px-6 lg:px-8 space-y-6">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-4 border-b border-slate-200 gap-4">
        <div>
            <div class="flex items-center gap-2 text-xs text-slate-500 font-mono mb-1">
                <a href="/admin/products" class="hover:text-slate-800">Products</a>
                <span>/</span>
                <span>{{ $isEditing ? 'Edit: ' . $name : 'New Product' }}</span>
            </div>
            <h1 class="text-xl sm:text-2xl font-bold tracking-tight text-slate-900">
                {{ $isEditing ? 'Edit Catalog Product' : 'Create Dropship Product' }}
            </h1>
        </div>

        <div class="flex items-center gap-3">
            <a href="/admin/products" class="px-4 py-2 text-xs font-medium text-slate-700 bg-white border border-slate-300 rounded-lg hover:bg-slate-50 min-h-[44px] flex items-center">
                Cancel
            </a>
            <button 
                type="button" 
                wire:click="save" 
                wire:loading.attr="disabled"
                class="px-5 py-2 text-xs font-bold text-white bg-[#0F4C81] hover:bg-[#0A355C] rounded-lg shadow-sm min-h-[44px] flex items-center gap-2 transition-all disabled:opacity-50"
            >
                <span wire:loading.remove wire:target="save">Save Product</span>
                <span wire:loading wire:target="save" class="flex items-center gap-1.5">
                    <svg class="animate-spin h-3.5 w-3.5 text-white" viewBox="0 0 24 24" fill="none"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg>
                    <span>Saving...</span>
                </span>
            </button>
        </div>
    </div>

    {{-- Notification flash --}}
    @if (session()->has('status'))
        <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl text-xs flex items-center gap-2">
            <svg class="w-4 h-4 text-[#28A745] shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
            <span>{{ session('status') }}</span>
        </div>
    @endif

    <form wire:submit.prevent="save" class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        {{-- Main Left Form --}}
        <div class="lg:col-span-8 space-y-6">
            {{-- Basic Information --}}
            <div class="bg-white border border-slate-200 rounded-xl p-5 shadow-xs space-y-4">
                <h3 class="text-sm font-semibold text-slate-900 border-b border-slate-100 pb-2">Product Information</h3>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Product Title *</label>
                    <input 
                        type="text" 
                        wire:model.live.debounce.300ms="name"
                        placeholder="e.g. Cyclone TWS Pro Wireless Earbuds"
                        class="w-full px-3 py-2 text-xs bg-slate-50 border @error('name') border-rose-400 @else border-slate-200 @enderror rounded-lg focus:bg-white focus:ring-1 focus:ring-[#0F4C81]"
                    />
                    @error('name') <span class="text-[11px] text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">URL Slug *</label>
                        <input 
                            type="text" 
                            wire:model="slug"
                            placeholder="cyclone-tws-pro-wireless-earbuds"
                            class="w-full px-3 py-2 text-xs font-mono bg-slate-50 border @error('slug') border-rose-400 @else border-slate-200 @enderror rounded-lg"
                        />
                        @error('slug') <span class="text-[11px] text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Master SKU *</label>
                        <input 
                            type="text" 
                            wire:model="sku"
                            placeholder="CM-PRD-8821"
                            class="w-full px-3 py-2 text-xs font-mono uppercase bg-slate-50 border @error('sku') border-rose-400 @else border-slate-200 @enderror rounded-lg"
                        />
                        @error('sku') <span class="text-[11px] text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Short Description (PDP Summary)</label>
                    <textarea 
                        wire:model="short_description" 
                        rows="2"
                        placeholder="Highlight 2-3 key selling points for mobile quick view..."
                        class="w-full px-3 py-2 text-xs bg-slate-50 border border-slate-200 rounded-lg"
                    ></textarea>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Detailed Description (Full HTML / Spec)</label>
                    <textarea 
                        wire:model="description" 
                        rows="5"
                        placeholder="Comprehensive specifications, warranty details, and usage instructions..."
                        class="w-full px-3 py-2 text-xs font-sans bg-slate-50 border border-slate-200 rounded-lg"
                    ></textarea>
                </div>
            </div>

            {{-- Media & Images --}}
            <div class="bg-white border border-slate-200 rounded-xl p-5 shadow-xs space-y-4">
                <div class="flex items-center justify-between border-b border-slate-100 pb-2">
                    <h3 class="text-sm font-semibold text-slate-900">Product Photography & Gallery</h3>
                    <span class="text-[11px] text-slate-500">Max 2MB per photo</span>
                </div>

                {{-- Existing Images --}}
                @if (!empty($existingImages))
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                        @foreach ($existingImages as $img)
                            <div class="relative group rounded-lg overflow-hidden border border-slate-200 bg-slate-50 p-1 aspect-square flex items-center justify-center">
                                <img src="{{ $img['image_path'] }}" alt="Product Image" class="object-cover w-full h-full rounded" />
                                <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity flex flex-col items-center justify-center gap-1 p-2">
                                    @if ($img['is_primary'])
                                        <span class="text-[10px] bg-[#28A745] text-white px-2 py-0.5 rounded font-medium">Primary</span>
                                    @else
                                        <button type="button" wire:click="setPrimaryImage({{ $img['id'] }})" class="text-[10px] bg-white text-slate-900 px-2 py-0.5 rounded hover:bg-slate-100">Set Primary</button>
                                    @endif
                                    <button type="button" wire:click="deleteExistingImage({{ $img['id'] }})" class="text-[10px] bg-rose-600 text-white px-2 py-0.5 rounded hover:bg-rose-700">Delete</button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif

                {{-- Upload Area --}}
                <div class="border-2 border-dashed border-slate-200 hover:border-[#0F4C81] transition-colors rounded-xl p-6 text-center">
                    <input type="file" wire:model="images" multiple id="fileUpload" class="hidden" accept="image/*" />
                    <label for="fileUpload" class="cursor-pointer block space-y-2">
                        <svg class="mx-auto h-8 w-8 text-slate-400" stroke="currentColor" fill="none" viewBox="0 0 48 48"><path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" /></svg>
                        <div class="text-xs text-slate-600">
                            <span class="font-semibold text-[#0F4C81]">Click to upload</span> or drag and drop images
                        </div>
                        <p class="text-[10px] text-slate-400">PNG, JPG, WebP up to 2MB</p>
                    </label>
                </div>
            </div>

            {{-- Variants Section --}}
            <div class="bg-white border border-slate-200 rounded-xl p-5 shadow-xs space-y-4">
                <div class="flex items-center justify-between border-b border-slate-100 pb-2">
                    <div>
                        <h3 class="text-sm font-semibold text-slate-900">Product Variants</h3>
                        <p class="text-[11px] text-slate-500">Configure size, color, or bundle variations.</p>
                    </div>
                    <button 
                        type="button" 
                        wire:click="addVariant"
                        class="px-3 py-1.5 text-xs font-semibold text-[#0F4C81] bg-blue-50 hover:bg-blue-100 rounded-lg min-h-[44px] flex items-center gap-1.5 transition-colors"
                    >
                        <span>+ Add Variant</span>
                    </button>
                </div>

                @if ($has_variants && count($variants) > 0)
                    <div class="space-y-3">
                        @foreach ($variants as $index => $variant)
                            <div class="p-3.5 bg-slate-50 border border-slate-200 rounded-xl space-y-3">
                                <div class="flex items-center justify-between">
                                    <span class="text-xs font-mono font-bold text-slate-800">Variant #{{ $index + 1 }}</span>
                                    <button type="button" wire:click="removeVariant({{ $index }})" class="text-xs text-rose-600 hover:text-rose-800">Remove</button>
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 text-xs">
                                    <div>
                                        <label class="block text-[11px] text-slate-600 mb-0.5">Attribute Type</label>
                                        <input type="text" wire:model="variants.{{ $index }}.attribute_name" placeholder="e.g. Color or Size" class="w-full p-2 bg-white border border-slate-200 rounded text-xs" />
                                    </div>
                                    <div>
                                        <label class="block text-[11px] text-slate-600 mb-0.5">Attribute Value</label>
                                        <input type="text" wire:model="variants.{{ $index }}.attribute_value" placeholder="e.g. Midnight Blue / XL" class="w-full p-2 bg-white border border-slate-200 rounded text-xs" />
                                    </div>
                                    <div>
                                        <label class="block text-[11px] text-slate-600 mb-0.5">Variant SKU</label>
                                        <input type="text" wire:model="variants.{{ $index }}.sku" class="w-full p-2 font-mono uppercase bg-white border border-slate-200 rounded text-xs" />
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 text-xs">
                                    <div>
                                        <label class="block text-[11px] text-slate-600 mb-0.5">Wholesale Cost (BDT)</label>
                                        <input type="number" step="0.01" wire:model="variants.{{ $index }}.cost_price" class="w-full p-2 font-mono bg-white border border-slate-200 rounded text-xs" />
                                    </div>
                                    <div>
                                        <label class="block text-[11px] text-slate-600 mb-0.5">Selling Price (BDT)</label>
                                        <input type="number" step="0.01" wire:model="variants.{{ $index }}.selling_price" class="w-full p-2 font-mono bg-white border border-slate-200 rounded text-xs" />
                                    </div>
                                    <div>
                                        <label class="block text-[11px] text-slate-600 mb-0.5">Stock Quantity</label>
                                        <input type="number" wire:model="variants.{{ $index }}.stock_quantity" class="w-full p-2 font-mono bg-white border border-slate-200 rounded text-xs" />
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-6 bg-slate-50 rounded-xl border border-dashed border-slate-200">
                        <p class="text-xs text-slate-500">This product currently has no variants (Simple Product mode).</p>
                    </div>
                @endif
            </div>
        </div>

        {{-- Right Sidebar --}}
        <div class="lg:col-span-4 space-y-6">
            {{-- Dropshipping Financials & Margin Tracker --}}
            <div class="bg-white border border-slate-200 rounded-xl p-5 shadow-xs space-y-4">
                <h3 class="text-sm font-semibold text-slate-900 border-b border-slate-100 pb-2">Pricing & Margin (BDT)</h3>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Wholesale Cost Price (৳) *</label>
                    <input 
                        type="number" 
                        step="0.01"
                        wire:model.live="cost_price"
                        class="w-full px-3 py-2 text-xs font-mono bg-slate-50 border border-slate-200 rounded-lg"
                    />
                    <span class="text-[10px] text-slate-400">Supplier dropship cost</span>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Customer Selling Price (৳) *</label>
                    <input 
                        type="number" 
                        step="0.01"
                        wire:model.live="selling_price"
                        class="w-full px-3 py-2 text-xs font-mono bg-slate-50 border border-slate-200 rounded-lg"
                    />
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Compare At / Crossed-out Price (৳)</label>
                    <input 
                        type="number" 
                        step="0.01"
                        wire:model="compare_at_price"
                        placeholder="e.g. 1500.00"
                        class="w-full px-3 py-2 text-xs font-mono bg-slate-50 border border-slate-200 rounded-lg"
                    />
                </div>

                {{-- Real-Time Margin Card --}}
                <div class="p-3 bg-emerald-50/80 border border-emerald-200 rounded-xl space-y-1">
                    <div class="flex items-center justify-between text-xs font-semibold text-emerald-900">
                        <span>Net Dropship Margin:</span>
                        <span class="font-mono text-sm font-bold text-[#28A745]">৳{{ number_format($this->estimatedMargin, 2) }}</span>
                    </div>
                    <div class="flex items-center justify-between text-[11px] text-emerald-700">
                        <span>Profit Percentage:</span>
                        <span class="font-mono font-semibold">{{ $this->marginPercentage }}%</span>
                    </div>
                </div>
            </div>

            {{-- Supplier & Category Links --}}
            <div class="bg-white border border-slate-200 rounded-xl p-5 shadow-xs space-y-4">
                <h3 class="text-sm font-semibold text-slate-900 border-b border-slate-100 pb-2">Organization</h3>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Assigned Supplier *</label>
                    <select wire:model="supplier_id" class="w-full px-3 py-2 text-xs bg-slate-50 border border-slate-200 rounded-lg min-h-[44px]">
                        <option value="">Select Local Supplier</option>
                        @foreach ($suppliers as $supplier)
                            <option value="{{ $supplier->id }}">{{ $supplier->company_name }} ({{ $supplier->contact_person }})</option>
                        @endforeach
                    </select>
                    @error('supplier_id') <span class="text-[11px] text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Catalog Category *</label>
                    <select wire:model="category_id" class="w-full px-3 py-2 text-xs bg-slate-50 border border-slate-200 rounded-lg min-h-[44px]">
                        <option value="">Select Category</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                    @error('category_id') <span class="text-[11px] text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                </div>
            </div>

            {{-- Inventory & Shipping Specs --}}
            <div class="bg-white border border-slate-200 rounded-xl p-5 shadow-xs space-y-4">
                <h3 class="text-sm font-semibold text-slate-900 border-b border-slate-100 pb-2">Inventory & Weight</h3>

                @if (!$has_variants)
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Stock Quantity *</label>
                        <input type="number" wire:model="stock_quantity" class="w-full px-3 py-2 text-xs font-mono bg-slate-50 border border-slate-200 rounded-lg" />
                    </div>
                @endif

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Low Stock Warning Threshold</label>
                    <input type="number" wire:model="low_stock_threshold" class="w-full px-3 py-2 text-xs font-mono bg-slate-50 border border-slate-200 rounded-lg" />
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Weight (Grams) — Courier SLA</label>
                    <input type="number" wire:model="weight_grams" class="w-full px-3 py-2 text-xs font-mono bg-slate-50 border border-slate-200 rounded-lg" />
                    <span class="text-[10px] text-slate-400">Used for courier surcharge estimation</span>
                </div>
            </div>

            {{-- Visibility Flags --}}
            <div class="bg-white border border-slate-200 rounded-xl p-5 shadow-xs space-y-3">
                <h3 class="text-sm font-semibold text-slate-900 border-b border-slate-100 pb-2">Visibility</h3>

                <label class="flex items-center gap-3 cursor-pointer min-h-[44px]">
                    <input type="checkbox" wire:model="is_active" class="w-4 h-4 text-[#0F4C81] rounded border-slate-300 focus:ring-[#0F4C81]" />
                    <span class="text-xs text-slate-700 font-medium">Active in Storefront</span>
                </label>

                <label class="flex items-center gap-3 cursor-pointer min-h-[44px]">
                    <input type="checkbox" wire:model="is_featured" class="w-4 h-4 text-[#FF6B35] rounded border-slate-300 focus:ring-[#FF6B35]" />
                    <span class="text-xs text-slate-700 font-medium">Feature on Homepage / Top Deals</span>
                </label>
            </div>
        </div>
    </form>
</div>

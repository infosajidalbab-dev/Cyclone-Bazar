<?php

namespace App\Livewire\Admin;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductVariant;
use App\Models\Supplier;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithFileUploads;

class ProductForm extends Component
{
    use WithFileUploads;

    public ?int $productId = null;
    public bool $isEditing = false;

    // Form Fields
    public ?int $supplier_id = null;
    public ?int $category_id = null;
    public string $name = '';
    public string $slug = '';
    public string $sku = '';
    public string $short_description = '';
    public string $description = '';
    public float $cost_price = 0.00;
    public float $selling_price = 0.00;
    public ?float $compare_at_price = null;
    public int $stock_quantity = 0;
    public int $low_stock_threshold = 5;
    public int $weight_grams = 250;
    public bool $is_active = true;
    public bool $is_featured = false;
    public bool $has_variants = false;

    // Images
    public array $images = [];
    public array $existingImages = [];

    // Variants list
    public array $variants = [];

    protected function rules(): array
    {
        return [
            'supplier_id' => 'required|exists:suppliers,id',
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|min:3|max:255',
            'slug' => 'required|string|max:255|unique:products,slug,' . $this->productId,
            'sku' => 'required|string|max:64|unique:products,sku,' . $this->productId,
            'short_description' => 'nullable|string|max:500',
            'description' => 'nullable|string',
            'cost_price' => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|gt:0',
            'compare_at_price' => 'nullable|numeric|gte:selling_price',
            'stock_quantity' => 'required|integer|min:0',
            'low_stock_threshold' => 'required|integer|min:1',
            'weight_grams' => 'required|integer|min:10',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'has_variants' => 'boolean',
            'images.*' => 'nullable|image|max:2048', // 2MB max
            'variants.*.sku' => 'required_if:has_variants,true|string|max:64',
            'variants.*.title' => 'required_if:has_variants,true|string|max:100',
            'variants.*.attribute_name' => 'required_if:has_variants,true|string|max:50',
            'variants.*.attribute_value' => 'required_if:has_variants,true|string|max:50',
            'variants.*.cost_price' => 'required_if:has_variants,true|numeric|min:0',
            'variants.*.selling_price' => 'required_if:has_variants,true|numeric|gt:0',
            'variants.*.stock_quantity' => 'required_if:has_variants,true|integer|min:0',
        ];
    }

    public function mount(?int $id = null): void
    {
        if ($id) {
            $this->productId = $id;
            $this->isEditing = true;
            $this->loadProduct();
        } else {
            // Defaults for new product
            $this->sku = 'CM-PRD-' . strtoupper(Str::random(6));
            $firstSupplier = Supplier::where('status', 'active')->first();
            $this->supplier_id = $firstSupplier?->id;
            $firstCategory = Category::where('is_active', true)->first();
            $this->category_id = $firstCategory?->id;
        }
    }

    public function loadProduct(): void
    {
        $product = Product::with(['images', 'variants'])->findOrFail($this->productId);

        $this->supplier_id = $product->supplier_id;
        $this->category_id = $product->category_id;
        $this->name = $product->name;
        $this->slug = $product->slug;
        $this->sku = $product->sku;
        $this->short_description = $product->short_description ?? '';
        $this->description = $product->description ?? '';
        $this->cost_price = (float) $product->cost_price;
        $this->selling_price = (float) $product->selling_price;
        $this->compare_at_price = $product->compare_at_price ? (float) $product->compare_at_price : null;
        $this->stock_quantity = $product->stock_quantity;
        $this->low_stock_threshold = $product->low_stock_threshold;
        $this->weight_grams = $product->weight_grams;
        $this->is_active = (bool) $product->is_active;
        $this->is_featured = (bool) $product->is_featured;
        $this->has_variants = (bool) $product->has_variants;

        $this->existingImages = $product->images->map(fn($img) => [
            'id' => $img->id,
            'image_path' => $img->image_path,
            'is_primary' => $img->is_primary,
        ])->toArray();

        $this->variants = $product->variants->map(fn($v) => [
            'id' => $v->id,
            'sku' => $v->sku,
            'title' => $v->title,
            'attribute_name' => $v->attribute_name,
            'attribute_value' => $v->attribute_value,
            'cost_price' => (float) $v->cost_price,
            'selling_price' => (float) $v->selling_price,
            'stock_quantity' => $v->stock_quantity,
            'is_active' => (bool) $v->is_active,
        ])->toArray();
    }

    public function updatedName($value): void
    {
        if (!$this->isEditing) {
            $this->slug = Str::slug($value);
        }
    }

    public function addVariant(): void
    {
        $this->has_variants = true;
        $index = count($this->variants) + 1;
        $this->variants[] = [
            'id' => null,
            'sku' => $this->sku . '-V' . $index,
            'title' => 'Standard / Option ' . $index,
            'attribute_name' => 'Size',
            'attribute_value' => 'M',
            'cost_price' => $this->cost_price,
            'selling_price' => $this->selling_price,
            'stock_quantity' => 10,
            'is_active' => true,
        ];
    }

    public function removeVariant(int $index): void
    {
        unset($this->variants[$index]);
        $this->variants = array_values($this->variants);
        if (empty($this->variants)) {
            $this->has_variants = false;
        }
    }

    public function setPrimaryImage(int $imageId): void
    {
        if ($this->productId) {
            ProductImage::where('product_id', $this->productId)->update(['is_primary' => false]);
            ProductImage::where('id', $imageId)->update(['is_primary' => true]);
            $this->loadProduct();
        }
    }

    public function deleteExistingImage(int $imageId): void
    {
        $image = ProductImage::find($imageId);
        if ($image && $image->product_id === $this->productId) {
            $image->delete();
            $this->loadProduct();
        }
    }

    public function getEstimatedMarginProperty(): float
    {
        return $this->selling_price > 0 ? ($this->selling_price - $this->cost_price) : 0.00;
    }

    public function getMarginPercentageProperty(): float
    {
        if ($this->cost_price <= 0 || $this->selling_price <= 0) return 0.0;
        return round((($this->selling_price - $this->cost_price) / $this->selling_price) * 100, 1);
    }

    public function save()
    {
        $this->validate();

        return DB::transaction(function () {
            $productData = [
                'supplier_id' => $this->supplier_id,
                'category_id' => $this->category_id,
                'name' => trim($this->name),
                'slug' => Str::slug($this->slug ?: $this->name),
                'sku' => strtoupper(trim($this->sku)),
                'short_description' => $this->short_description,
                'description' => $this->description,
                'cost_price' => $this->cost_price,
                'selling_price' => $this->selling_price,
                'compare_at_price' => $this->compare_at_price,
                'stock_quantity' => $this->has_variants ? array_sum(array_column($this->variants, 'stock_quantity')) : $this->stock_quantity,
                'low_stock_threshold' => $this->low_stock_threshold,
                'weight_grams' => $this->weight_grams,
                'is_active' => $this->is_active,
                'is_featured' => $this->is_featured,
                'has_variants' => $this->has_variants,
            ];

            if ($this->isEditing && $this->productId) {
                $product = Product::findOrFail($this->productId);
                $product->update($productData);
            } else {
                $product = Product::create($productData);
                $this->productId = $product->id;
                $this->isEditing = true;
            }

            // Save Uploaded Images
            if (!empty($this->images)) {
                $hasExistingPrimary = $product->images()->where('is_primary', true)->exists();

                foreach ($this->images as $index => $uploadedImage) {
                    $path = $uploadedImage->store('products', 'public');
                    $isPrimary = (!$hasExistingPrimary && $index === 0);

                    $product->images()->create([
                        'image_path' => '/storage/' . $path,
                        'alt_text' => $product->name,
                        'is_primary' => $isPrimary,
                        'sort_order' => $index,
                    ]);
                }
                $this->images = [];
            }

            // Sync Variants
            if ($this->has_variants) {
                $existingVariantIds = [];

                foreach ($this->variants as $variantData) {
                    if (!empty($variantData['id'])) {
                        $variant = ProductVariant::find($variantData['id']);
                        if ($variant && $variant->product_id === $product->id) {
                            $variant->update([
                                'sku' => $variantData['sku'],
                                'title' => $variantData['title'],
                                'attribute_name' => $variantData['attribute_name'],
                                'attribute_value' => $variantData['attribute_value'],
                                'cost_price' => $variantData['cost_price'],
                                'selling_price' => $variantData['selling_price'],
                                'stock_quantity' => $variantData['stock_quantity'],
                                'is_active' => $variantData['is_active'] ?? true,
                            ]);
                            $existingVariantIds[] = $variant->id;
                        }
                    } else {
                        $newVariant = $product->variants()->create([
                            'sku' => $variantData['sku'],
                            'title' => $variantData['title'],
                            'attribute_name' => $variantData['attribute_name'],
                            'attribute_value' => $variantData['attribute_value'],
                            'cost_price' => $variantData['cost_price'],
                            'selling_price' => $variantData['selling_price'],
                            'stock_quantity' => $variantData['stock_quantity'],
                            'is_active' => $variantData['is_active'] ?? true,
                        ]);
                        $existingVariantIds[] = $newVariant->id;
                    }
                }

                // Delete variants no longer in array
                $product->variants()->whereNotIn('id', $existingVariantIds)->delete();
            } else {
                $product->variants()->delete();
            }

            $this->loadProduct();
            session()->flash('status', 'Product saved successfully! All inventory and margin calculations are synced.');
        });
    }

    public function render()
    {
        return view('livewire.admin.product-form', [
            'suppliers' => Supplier::where('status', 'active')->orderBy('name')->get(),
            'categories' => Category::where('is_active', true)->orderBy('name')->get(),
        ]);
    }
}

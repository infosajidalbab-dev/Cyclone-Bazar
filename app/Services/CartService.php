<?php

namespace App\Services;

use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use InvalidArgumentException;

class CartService
{
    protected const SESSION_KEY = 'cyclone_cart';

    /**
     * Get all cart items with fresh product/variant model details.
     *
     * @return array<string, array{
     *     cart_key: string,
     *     product_id: int,
     *     variant_id: int|null,
     *     product_name: string,
     *     variant_title: string|null,
     *     sku: string,
     *     unit_price: float,
     *     quantity: int,
     *     subtotal: float,
     *     image_path: string|null,
     *     max_stock: int
     * }>
     */
    public function getItems(): array
    {
        $rawCart = Session::get(self::SESSION_KEY, []);
        $items = [];

        foreach ($rawCart as $cartKey => $item) {
            $product = Product::with(['primaryImage', 'images'])->find($item['product_id']);
            if (!$product || !$product->is_active) {
                $this->remove($cartKey);
                continue;
            }

            $variantTitle = null;
            $unitPrice = (float) $product->selling_price;
            $sku = $product->sku;
            $maxStock = $product->stock_quantity;

            if (!empty($item['variant_id'])) {
                $variant = ProductVariant::find($item['variant_id']);
                if (!$variant || !$variant->is_active) {
                    $this->remove($cartKey);
                    continue;
                }
                $variantTitle = $variant->title;
                $unitPrice = (float) $variant->selling_price;
                $sku = $variant->sku;
                $maxStock = $variant->stock_quantity;
            }

            $quantity = min($item['quantity'], max(1, $maxStock));
            $subtotal = $unitPrice * $quantity;

            $imagePath = $product->primaryImage?->image_path 
                ?? $product->images->first()?->image_path 
                ?? null;

            $items[$cartKey] = [
                'cart_key' => $cartKey,
                'product_id' => $product->id,
                'variant_id' => $item['variant_id'] ?? null,
                'product_name' => $product->name,
                'variant_title' => $variantTitle,
                'sku' => $sku,
                'unit_price' => $unitPrice,
                'quantity' => $quantity,
                'subtotal' => $subtotal,
                'image_path' => $imagePath,
                'max_stock' => $maxStock,
            ];
        }

        return $items;
    }

    /**
     * Add product to cart.
     */
    public function add(int $productId, ?int $variantId = null, int $quantity = 1): void
    {
        if ($quantity < 1) {
            throw new InvalidArgumentException("Quantity must be at least 1.");
        }

        $product = Product::where('id', $productId)->where('is_active', true)->firstOrFail();
        $cartKey = $this->generateCartKey($productId, $variantId);

        $maxStock = $product->stock_quantity;
        if ($variantId) {
            $variant = ProductVariant::where('id', $variantId)
                ->where('product_id', $productId)
                ->where('is_active', true)
                ->firstOrFail();
            $maxStock = $variant->stock_quantity;
        }

        $cart = Session::get(self::SESSION_KEY, []);

        $currentQty = isset($cart[$cartKey]) ? $cart[$cartKey]['quantity'] : 0;
        $newQty = min($maxStock, $currentQty + $quantity);

        if ($newQty < 1) {
            throw new InvalidArgumentException("Requested item is out of stock.");
        }

        $cart[$cartKey] = [
            'product_id' => $productId,
            'variant_id' => $variantId,
            'quantity' => $newQty,
        ];

        Session::put(self::SESSION_KEY, $cart);
    }

    /**
     * Update quantity for a specific cart key.
     */
    public function updateQuantity(string $cartKey, int $quantity): void
    {
        $cart = Session::get(self::SESSION_KEY, []);

        if (!isset($cart[$cartKey])) {
            return;
        }

        if ($quantity <= 0) {
            $this->remove($cartKey);
            return;
        }

        $item = $cart[$cartKey];
        $maxStock = 99;

        if (!empty($item['variant_id'])) {
            $variant = ProductVariant::find($item['variant_id']);
            $maxStock = $variant ? $variant->stock_quantity : 0;
        } else {
            $product = Product::find($item['product_id']);
            $maxStock = $product ? $product->stock_quantity : 0;
        }

        $cart[$cartKey]['quantity'] = min($quantity, $maxStock);
        Session::put(self::SESSION_KEY, $cart);
    }

    /**
     * Remove item from cart.
     */
    public function remove(string $cartKey): void
    {
        $cart = Session::get(self::SESSION_KEY, []);
        unset($cart[$cartKey]);
        Session::put(self::SESSION_KEY, $cart);
    }

    /**
     * Empty entire shopping cart.
     */
    public function clear(): void
    {
        Session::forget(self::SESSION_KEY);
    }

    /**
     * Calculate cart subtotal (BDT).
     */
    public function getSubtotal(): float
    {
        $items = $this->getItems();
        return (float) array_sum(array_column($items, 'subtotal'));
    }

    /**
     * Total item count in cart.
     */
    public function getItemCount(): int
    {
        $items = $this->getItems();
        return (int) array_sum(array_column($items, 'quantity'));
    }

    /**
     * Check if cart is empty.
     */
    public function isEmpty(): bool
    {
        return empty(Session::get(self::SESSION_KEY, []));
    }

    /**
     * Unique key generator combining product and variant IDs.
     */
    protected function generateCartKey(int $productId, ?int $variantId = null): string
    {
        return $variantId ? "item_{$productId}_var_{$variantId}" : "item_{$productId}_base";
    }
}

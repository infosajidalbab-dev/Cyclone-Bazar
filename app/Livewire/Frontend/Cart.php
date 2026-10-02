<?php

namespace App\Livewire\Frontend;

use App\Models\DeliveryZone;
use App\Services\CartService;
use Livewire\Component;

class Cart extends Component
{
    public int $selectedZoneId = 1; // Default: Inside Dhaka

    protected $listeners = [
        'cartUpdated' => '$refresh',
    ];

    public function mount(CartService $cartService): void
    {
        $defaultZone = DeliveryZone::where('code', 'DHAKA_INSIDE')->first();
        if ($defaultZone) {
            $this->selectedZoneId = $defaultZone->id;
        }
    }

    public function incrementQuantity(string $cartKey, CartService $cartService): void
    {
        $items = $cartService->getItems();
        if (isset($items[$cartKey])) {
            $currentQty = $items[$cartKey]['quantity'];
            $maxStock = $items[$cartKey]['max_stock'];

            if ($currentQty < $maxStock) {
                $cartService->updateQuantity($cartKey, $currentQty + 1);
                $this->dispatch('cartCountUpdated');
            }
        }
    }

    public function decrementQuantity(string $cartKey, CartService $cartService): void
    {
        $items = $cartService->getItems();
        if (isset($items[$cartKey])) {
            $currentQty = $items[$cartKey]['quantity'];
            if ($currentQty > 1) {
                $cartService->updateQuantity($cartKey, $currentQty - 1);
            } else {
                $cartService->remove($cartKey);
            }
            $this->dispatch('cartCountUpdated');
        }
    }

    public function removeItem(string $cartKey, CartService $cartService): void
    {
        $cartService->remove($cartKey);
        $this->dispatch('cartCountUpdated');
        session()->flash('cart_status', 'আইটেম কার্ট থেকে সরানো হয়েছে (Item removed from cart).');
    }

    public function clearCart(CartService $cartService): void
    {
        $cartService->clear();
        $this->dispatch('cartCountUpdated');
    }

    public function render(CartService $cartService)
    {
        $cartItems = $cartService->getItems();
        $subtotal = $cartService->getSubtotal();
        $itemCount = $cartService->getItemCount();

        $zones = DeliveryZone::where('is_active', true)->get();
        $selectedZone = $zones->firstWhere('id', $this->selectedZoneId) ?? $zones->first();
        $deliveryCharge = $selectedZone ? (float) $selectedZone->base_charge : 60.00;
        $totalAmount = $subtotal + $deliveryCharge;

        return view('livewire.frontend.cart', [
            'items' => $cartItems,
            'subtotal' => $subtotal,
            'itemCount' => $itemCount,
            'zones' => $zones,
            'selectedZone' => $selectedZone,
            'deliveryCharge' => $deliveryCharge,
            'totalAmount' => $totalAmount,
        ]);
    }
}

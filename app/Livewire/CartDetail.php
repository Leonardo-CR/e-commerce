<?php

namespace App\Livewire;

use App\Models\CartItem;
use Livewire\Component;

class CartDetail extends Component
{
    public function removeItem($itemId)
    {
        $item = CartItem::where('idCart_Item', $itemId)
            ->whereHas('cart', function($q) {
                $q->where('user_id', auth()->id());
            })->first();

        if ($item) {
            $item->delete();
            $this->dispatch('cart-updated');
        }
    }

    public function updateQuantity($itemId, $quantity)
    {
        if ($quantity < 1) return;

        $item = CartItem::where('idCart_Item', $itemId)
            ->whereHas('cart', function($q) {
                $q->where('user_id', auth()->id());
            })->first();

        if ($item) {
            $item->quantity = $quantity;
            $item->subtotal = $quantity * $item->unit_price;
            $item->save();
            $this->dispatch('cart-updated');
        }
    }

    public function render()
    {
        $cart = auth()->user()->cart;
        $items = $cart ? $cart->cartItems()->with('earphone')->get() : collect();
        $total = $items->sum('subtotal');

        return view('livewire.cart-detail', [
            'items' => $items,
            'total' => $total
        ]);
    }
}

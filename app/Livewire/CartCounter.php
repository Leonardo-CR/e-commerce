<?php

namespace App\Livewire;

use Livewire\Attributes\On;
use Livewire\Component;

class CartCounter extends Component
{
    #[On('cart-updated')]
    public function render()
    {
        $count = auth()->check() ? (auth()->user()->cart?->cartItems->sum('quantity') ?? 0) : 0;

        return view('livewire.cart-counter', ['count' => $count]);
    }
}

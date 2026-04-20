<?php

namespace App\Livewire;

use App\Models\Earphone;
use Livewire\Component;
use Livewire\WithPagination;

class HeadphoneCatalogue extends Component
{
    use WithPagination;

    public $search = '';
    public $selectedBrands = [];
    public $minPrice = 0;
    public $maxPrice = 5000;
    public $colors = [];
    public $types = [];
    public $selectedColors = []; // Track selection per product in listing

    public function mount()
    {
        // Default to first color for all products
        $this->selectedColors = Earphone::pluck('idEarphone')
            ->mapWithKeys(fn($id) => [$id => 0])
            ->toArray();
    }

    public function selectColor($productId, $index)
    {
        $this->selectedColors[$productId] = $index;
    }

    // Reset pagination when search/filters change
    public function updated($propertyName)
    {
        if (in_array($propertyName, ['search', 'minPrice', 'maxPrice', 'selectedBrands', 'colors'])) {
            $this->resetPage();
        }
    }

    public function render()
    {
        $query = Earphone::query();

        if ($this->search) {
            $query->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('description', 'like', '%' . $this->search . '%');
        }

        if (!empty($this->selectedBrands)) {
            $query->where(function($q) {
                foreach ($this->selectedBrands as $brand) {
                    $q->orWhere('name', 'like', '%' . $brand . '%');
                }
            });
        }

        // Rango de precio
        $query->whereBetween('price', [$this->minPrice, $this->maxPrice]);

        if (!empty($this->colors)) {
            $query->where(function($q) {
                foreach ($this->colors as $color) {
                    $q->orWhere('description', 'like', '%' . $color . '%');
                }
            });
        }

        $products = $query->paginate(12);

        return view('livewire.headphone-catalogue', [
            'products' => $products
        ]);
    }

    public function addToCart($productId)
    {
        if (!auth()->check()) {
            $this->dispatch('show-auth-modal');
            return;
        }

        $user = auth()->user();
        $product = Earphone::findOrFail($productId);
        
        $colorIndex = $this->selectedColors[$productId] ?? 0;
        $selectedHex = $product->colors[$colorIndex]['hex'] ?? null;

        // Get or create active cart
        $cart = \App\Models\Cart::firstOrCreate(
            ['user_id' => $user->id, 'status' => 'active'],
            ['status' => 'active']
        );

        // Add or update item
        $cartItem = \App\Models\CartItem::where('idCart', $cart->idCart)
            ->where('idEarphone', $productId)
            ->where('color', $selectedHex) // Match same product AND same color
            ->first();

        if ($cartItem) {
            $cartItem->increment('quantity');
            $cartItem->subtotal = $cartItem->quantity * $cartItem->unit_price;
            $cartItem->save();
        } else {
            \App\Models\CartItem::create([
                'idCart' => $cart->idCart,
                'idEarphone' => $productId,
                'quantity' => 1,
                'unit_price' => $product->price,
                'subtotal' => $product->price,
                'color' => $selectedHex
            ]);
        }

        session()->flash('message', '¡Producto añadido al carrito!');
        $this->dispatch('cart-updated');
    }
}

<?php

namespace App\Livewire;

use App\Models\Color;
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
    public $selectedColors = [];

    public function mount()
    {
        $this->selectedColors = Earphone::pluck('idEarphone')
            ->mapWithKeys(fn($id) => [$id => 0])
            ->toArray();
    }

    public function selectColor($productId, $index)
    {
        $this->selectedColors[$productId] = $index;
    }

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
            $query->where(function ($q) {
                foreach ($this->selectedBrands as $brand) {
                    $q->orWhere('name', 'like', '%' . $brand . '%');
                }
            });
        }

        $query->whereBetween('price', [$this->minPrice, $this->maxPrice]);

        if (!empty($this->colors)) {
            $query->where(function ($q) {
                foreach ($this->colors as $colorId) {
                    $q->orWhereJsonContains('colors', ['color_id' => (int) $colorId]);
                }
            });
        }

        $products = $query->paginate(12);

        $colorsMap = Color::all()->keyBy('id');
        $availableColors = Color::orderBy('name')->get();

        return view('livewire.headphone-catalogue', [
            'products'        => $products,
            'colorsMap'       => $colorsMap,
            'availableColors' => $availableColors,
        ]);
    }

    public function addToCart($productId)
    {
        if (!auth()->check()) {
            $this->dispatch('show-auth-modal');
            return;
        }

        $user    = auth()->user();
        $product = Earphone::findOrFail($productId);

        $colorIndex = $this->selectedColors[$productId] ?? 0;
        $colorId    = $product->colors[$colorIndex]['color_id'] ?? null;

        $cart = \App\Models\Cart::firstOrCreate(
            ['user_id' => $user->id, 'status' => 'active'],
            ['status' => 'active']
        );

        $cartItem = \App\Models\CartItem::where('idCart', $cart->idCart)
            ->where('idEarphone', $productId)
            ->where('color_id', $colorId)
            ->first();

        if ($cartItem) {
            $cartItem->increment('quantity');
            $cartItem->subtotal = $cartItem->quantity * $cartItem->unit_price;
            $cartItem->save();
        } else {
            \App\Models\CartItem::create([
                'idCart'     => $cart->idCart,
                'idEarphone' => $productId,
                'quantity'   => 1,
                'unit_price' => $product->price,
                'subtotal'   => $product->price,
                'color_id'   => $colorId,
            ]);
        }

        session()->flash('message', '¡Producto añadido al carrito!');
        $this->dispatch('cart-updated');
    }
}

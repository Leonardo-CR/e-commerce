<?php

namespace Database\Factories;

use App\Models\Cart;
use App\Models\Earphone;
use Illuminate\Database\Eloquent\Factories\Factory;

class CartItemFactory extends Factory
{
    public function definition(): array
    {
        $unitPrice = fake()->randomFloat(2, 299, 4999);
        $quantity  = fake()->numberBetween(1, 4);

        return [
            'idCart'     => Cart::factory(),
            'idEarphone' => Earphone::factory(),
            'quantity'   => $quantity,
            'unit_price' => $unitPrice,
            'subtotal'   => round($unitPrice * $quantity, 2),
        ];
    }
}

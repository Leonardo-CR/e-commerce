<?php

namespace Database\Factories;

use App\Models\Earphone;
use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderItemFactory extends Factory
{
    public function definition(): array
    {
        $unitPrice = fake()->randomFloat(2, 299, 4999);
        $quantity  = fake()->numberBetween(1, 3);
        $discount  = fake()->boolean(25) ? fake()->randomFloat(2, 10, 200) : 0;
        $tax       = round($unitPrice * $quantity * 0.16, 2);
        $subtotal  = round(($unitPrice * $quantity) - $discount + $tax, 2);

        return [
            'idOrder'    => Order::factory(),
            'idEarphone' => Earphone::factory(),
            'quantity'   => $quantity,
            'unit_price' => $unitPrice,
            'discount'   => $discount,
            'tax'        => $tax,
            'subtotal'   => $subtotal,
        ];
    }
}

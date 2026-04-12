<?php

namespace Database\Factories;

use App\Models\Earphone;
use App\Models\Purchase;
use Illuminate\Database\Eloquent\Factories\Factory;

class PurchaseItemFactory extends Factory
{
    public function definition(): array
    {
        return [
            'idPurchase'    => Purchase::factory(),
            'idEarphone'    => Earphone::factory(),
            'quantity'      => fake()->numberBetween(10, 100),
            'unit_cost'     => fake()->randomFloat(2, 150, 2500),
            'received_date' => fake()->boolean(70)
                ? fake()->dateTimeBetween('-6 months', 'now')->format('Y-m-d')
                : null,
        ];
    }
}

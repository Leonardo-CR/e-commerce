<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class PurchaseFactory extends Factory
{
    public function definition(): array
    {
        $methods = ['Transferencia bancaria', 'Cheque', 'Crédito a 30 días', 'Contado', 'Carta de crédito'];
        $total   = fake()->randomFloat(2, 5000, 80000);
        $iva     = round($total * 0.16, 2);

        return [
            'purchaseDate'  => fake()->dateTimeBetween('-1 year', 'now')->format('Y-m-d'),
            'invoiceNumber' => 'FAC-' . fake()->numerify('####') . '-' . date('Y'),
            'paymentMethod' => fake()->randomElement($methods),
            'iva'           => $iva,
            'shipping_cost' => fake()->randomFloat(2, 200, 2000),
            'totalAmount'   => $total + $iva,
            'notes'         => fake()->boolean(40) ? fake()->sentence(10) : null,
        ];
    }
}

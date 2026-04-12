<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class PaymentFactory extends Factory
{
    public function definition(): array
    {
        $methods  = ['Tarjeta de crédito', 'Tarjeta de débito', 'PayPal', 'Transferencia bancaria', 'OXXO Pay', 'Mercado Pago'];
        $statuses = ['completado', 'pendiente', 'fallido', 'reembolsado'];

        return [
            'payment_date' => fake()->dateTimeBetween('-6 months', 'now'),
            'amount'       => fake()->randomFloat(2, 300, 15000),
            'status'       => fake()->randomElement($statuses),
            'method'       => fake()->randomElement($methods),
        ];
    }
}

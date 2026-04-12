<?php

namespace Database\Factories;

use App\Models\Payment;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    public function definition(): array
    {
        $companies = ['DHL', 'FedEx', 'Estafeta', 'Redpack', 'Correos de México', 'UPS', 'Paquetexpress'];
        $statuses  = ['pendiente', 'confirmado', 'en preparación', 'enviado', 'entregado', 'cancelado'];

        $shipping = fake()->randomFloat(2, 0, 299);
        $total    = fake()->randomFloat(2, 350, 15000);

        return [
            'user_id'         => User::factory(),
            'idPayment'       => fake()->boolean(80) ? Payment::factory() : null,
            'status'          => fake()->randomElement($statuses),
            'shippingCost'    => $shipping,
            'totalAmount'     => $total,
            'shippingCompany' => fake()->randomElement($companies),
            'TrackingNumber'  => strtoupper(fake()->bothify('??##########??')),
        ];
    }
}

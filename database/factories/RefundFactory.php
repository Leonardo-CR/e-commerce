<?php

namespace Database\Factories;

use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\Factory;

class RefundFactory extends Factory
{
    public function definition(): array
    {
        $reasons = [
            'Producto llegó dañado durante el envío.',
            'El artículo no corresponde a la descripción del producto.',
            'Llegó el modelo incorrecto, pedí otro color.',
            'El producto dejó de funcionar a los pocos días de uso.',
            'No me gustó la calidad del sonido, no es lo que esperaba.',
            'Cambié de opinión, ya no necesito el producto.',
            'Encontré el mismo producto más barato en otra tienda.',
            'El vendedor tardó demasiado en enviar el pedido.',
        ];

        $statuses = ['solicitado', 'en revisión', 'aprobado', 'rechazado', 'completado'];

        return [
            'idOrder' => Order::factory(),
            'reason'  => fake()->randomElement($reasons),
            'status'  => fake()->randomElement($statuses),
        ];
    }
}

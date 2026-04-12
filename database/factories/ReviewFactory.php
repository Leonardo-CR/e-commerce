<?php

namespace Database\Factories;

use App\Models\Earphone;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReviewFactory extends Factory
{
    public function definition(): array
    {
        $comments = [
            'Excelente calidad de sonido, superó mis expectativas. Los graves son increíbles.',
            'Muy cómodos para usar todo el día, la batería dura bastante. Los recomiendo.',
            'Buena relación calidad-precio. El cancelador de ruido funciona muy bien en transporte público.',
            'El envío fue rápido y el producto llegó en perfectas condiciones. Muy satisfecho.',
            'Son buenos audífonos pero el cable se siente un poco frágil. El sonido es claro.',
            'Me encantaron. La conexión Bluetooth es estable y la calidad de llamadas es perfecta.',
            'Producto tal y como se describe. El micrófono es claro en las videollamadas.',
            'Ligeros y con buen sonido. La almohadilla es cómoda. Vale cada centavo.',
            'Compré para mi hijo y quedó feliz. Buen sonido para gaming y música.',
            'Calidad de audio profesional a un precio accesible. Totalmente recomendados.',
        ];

        return [
            'user_id'    => User::factory(),
            'idEarphone' => Earphone::factory(),
            'comment'    => fake()->randomElement($comments),
        ];
    }
}

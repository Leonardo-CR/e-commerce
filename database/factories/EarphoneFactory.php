<?php

namespace Database\Factories;

use App\Models\Supplier;
use Illuminate\Database\Eloquent\Factories\Factory;

class EarphoneFactory extends Factory
{
    public function definition(): array
    {
        $brands   = ['Sony', 'JBL', 'Bose', 'Beats', 'AKG', 'Sennheiser', 'Audio-Technica', 'Jabra', 'Anker', 'Skullcandy'];
        $types    = ['Pro', 'Studio', 'Sport', 'Wireless', 'Noise Cancelling', 'Bass+', 'Air', 'Elite', 'Lite'];
        $brand    = fake()->randomElement($brands);
        $type     = fake()->randomElement($types);
        $model    = strtoupper(fake()->lexify('??')) . fake()->numerify('##');

        $descriptions = [
            'Audífonos de alta fidelidad con cancelación de ruido activa y batería de larga duración.',
            'Sonido envolvente 360° con graves profundos y agudos cristalinos, perfectos para el trabajo.',
            'Diseño ergonómico ultraligero, resistente al sudor, ideal para deporte y actividades al aire libre.',
            'Conexión inalámbrica Bluetooth 5.3 con latencia ultra baja para gaming y streaming.',
            'Micrófono integrado de alta sensibilidad para videollamadas y podcasting profesional.',
            'Diadema acolchada con almohadillas de cuero sintético para uso prolongado sin fatiga.',
            'Tecnología de reducción de ruido ambiental con modo transparente para mayor seguridad.',
            'Compatibles con asistentes de voz: Alexa, Google Assistant y Siri. Certificación IPX5.',
        ];

        return [
            'name'        => "{$brand} {$type} {$model}",
            'price'       => fake()->randomFloat(2, 299, 4999),
            'stock'       => fake()->numberBetween(5, 150),
            'description' => fake()->randomElement($descriptions),
            'image'       => null,
            'idSupplier'  => Supplier::factory(),
        ];
    }
}

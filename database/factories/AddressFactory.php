<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class AddressFactory extends Factory
{
    public function definition(): array
    {
        $states = [
            'Ciudad de México', 'Jalisco', 'Nuevo León', 'Puebla', 'Guanajuato',
            'Chihuahua', 'Veracruz', 'Michoacán', 'Oaxaca', 'Sonora',
            'Coahuila', 'Tamaulipas', 'Sinaloa', 'Baja California', 'Guerrero',
        ];

        return [
            'street'     => fake('es_MX')->streetName(),
            'number'     => fake()->buildingNumber(),
            'colony'     => 'Col. ' . fake('es_MX')->word() . ' ' . fake()->randomElement(['Norte', 'Sur', 'Centro', 'Alta', 'Nueva']),
            'city'       => fake('es_MX')->city(),
            'state'      => fake()->randomElement($states),
            'zip'        => fake()->numerify('#####'),
            'eliminated' => false,
            'user_id'    => User::factory(),
        ];
    }
}

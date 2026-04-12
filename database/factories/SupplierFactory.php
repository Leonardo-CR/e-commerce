<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class SupplierFactory extends Factory
{
    public function definition(): array
    {
        $companies = [
            'Electromax S.A. de C.V.',
            'Distribuidora AudioTech',
            'TechSound México',
            'Importaciones Sonido Pro',
            'Grupo Electrónica Nacional',
            'MexiSound Distribuciones',
            'AudioImport del Norte',
            'Soluciones Digitales MX',
        ];

        return [
            'name'    => fake()->randomElement($companies) . ' ' . fake()->randomLetter(),
            'phone'   => fake('es_MX')->phoneNumber(),
            'address' => fake('es_MX')->streetAddress() . ', ' . fake('es_MX')->city(),
            'email'   => fake()->unique()->companyEmail(),
        ];
    }
}

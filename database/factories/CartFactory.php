<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class CartFactory extends Factory
{
    public function definition(): array
    {
        $statuses = ['activo', 'pendiente', 'abandonado', 'convertido'];

        return [
            'user_id'   => User::factory(),
            'status'    => fake()->randomElement($statuses),
            'expressAt' => fake()->boolean(30) ? fake()->dateTimeBetween('now', '+7 days') : null,
        ];
    }
}

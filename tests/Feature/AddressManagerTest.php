<?php

use App\Models\User;
use App\Models\Address;
use App\Livewire\AddressManager;
use Livewire\Livewire;

test('addresses can be created', function () {
    $this->actingAs($user = User::factory()->create());

    Livewire::test(AddressManager::class)
        ->set('street', 'Calle Falsa')
        ->set('number', '123')
        ->set('colony', 'Centro')
        ->set('city', 'Monterrey')
        ->set('state', 'Nuevo León')
        ->set('zip', '64000')
        ->call('store');

    $this->assertDatabaseHas('addresses', [
        'user_id' => $user->id,
        'street' => 'Calle Falsa',
        'number' => '123',
        'colony' => 'Centro',
        'city' => 'Monterrey',
        'state' => 'Nuevo León',
        'zip' => '64000',
    ]);
});

test('addresses can be edited', function () {
    $this->actingAs($user = User::factory()->create());

    $address = Address::create([
        'user_id' => $user->id,
        'street' => 'Calle Vieja',
        'number' => '456',
        'colony' => 'Centro',
        'city' => 'Monterrey',
        'state' => 'Nuevo León',
        'zip' => '64000',
    ]);

    Livewire::test(AddressManager::class)
        ->call('edit', $address->idAddress)
        ->assertSet('street', 'Calle Vieja')
        ->assertSet('number', '456')
        ->set('street', 'Calle Nueva')
        ->set('number', '789')
        ->call('store');

    $this->assertDatabaseHas('addresses', [
        'idAddress' => $address->idAddress,
        'street' => 'Calle Nueva',
        'number' => '789',
    ]);
});

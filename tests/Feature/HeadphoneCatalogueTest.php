<?php

use App\Models\User;
use App\Models\Earphone;
use App\Livewire\HeadphoneCatalogue;
use Livewire\Livewire;

test('catalogue brand toggle works and filters products', function () {
    // Create ears
    Earphone::create([
        'name' => 'Apple AirPods Pro',
        'description' => 'Apple wireless earphones',
        'price' => 4500.00,
        'stock' => 10,
        'image_url' => 'http://example.com/airpods.jpg',
    ]);

    Earphone::create([
        'name' => 'Huawei FreeBuds 5i',
        'description' => 'Huawei wireless earphones',
        'price' => 1999.00,
        'stock' => 15,
        'image_url' => 'http://example.com/freebuds.jpg',
    ]);

    $component = Livewire::test(HeadphoneCatalogue::class);

    // Initial state: both should be present
    $component->assertSee('Apple AirPods Pro')
        ->assertSee('Huawei FreeBuds 5i');

    // Toggle Apple
    $component->call('toggleBrand', 'Apple')
        ->assertSee('Apple AirPods Pro')
        ->assertDontSee('Huawei FreeBuds 5i');

    // Toggle Huawei as well (both checked)
    $component->call('toggleBrand', 'Huawei')
        ->assertSee('Apple AirPods Pro')
        ->assertSee('Huawei FreeBuds 5i');

    // Untoggle Apple (only Huawei checked)
    $component->call('toggleBrand', 'Apple')
        ->assertDontSee('Apple AirPods Pro')
        ->assertSee('Huawei FreeBuds 5i');
});

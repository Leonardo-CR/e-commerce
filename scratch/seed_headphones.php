<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->handle(Illuminate\Http\Request::capture());

use App\Models\Supplier;
use App\Models\Earphone;

$s = Supplier::firstOrCreate(['email' => 'supplier@example.com'], [
    'name' => 'General Supplier',
    'phone' => '1234567890',
    'address' => 'Test Address',
]);

Earphone::create(['name' => 'HUAWEI FreeBuds Pro 3', 'price' => 99, 'stock' => 10, 'image' => 'images/products/huawei.png', 'idSupplier' => $s->idSupplier]);
Earphone::create(['name' => 'Apple Airpods pro max 2', 'price' => 59, 'stock' => 5, 'image' => 'images/products/apple.png', 'idSupplier' => $s->idSupplier]);
Earphone::create(['name' => 'Samsung Galaxy Buds 3', 'price' => 49, 'stock' => 15, 'image' => 'images/products/samsung.png', 'idSupplier' => $s->idSupplier]);

echo "Seeded successfully\n";

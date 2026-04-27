<?php
require 'vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$addresses = \App\Models\Address::all();
foreach($addresses as $addr) {
    echo "ID: " . $addr->idAddress . " - State: '" . $addr->state . "'\n";
}

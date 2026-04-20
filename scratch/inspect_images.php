<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->handle(Illuminate\Http\Request::capture());

use App\Models\Earphone;

foreach(Earphone::all() as $e) {
    echo "ID: {$e->idEarphone} | Name: {$e->name} | Image: {$e->image}\n";
}

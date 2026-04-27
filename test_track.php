<?php
require 'vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$envia = app(\App\Services\EnviaService::class);

try {
    $track = $envia->track('1234567890');
    echo "Tracking keys: " . implode(", ", array_keys($track)) . "\n";
    echo "Status: " . ($track['status'] ?? 'N/A') . "\n";
    echo "Carrier: " . ($track['carrier'] ?? 'N/A') . "\n";
    echo "Estimated Delivery: " . ($track['estimatedDelivery'] ?? 'N/A') . "\n";
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

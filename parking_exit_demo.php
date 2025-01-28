<?php
require 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Parkir\Kendaraan;
use Carbon\Carbon;

// Simulate time passage by setting a custom entry time
function simulateParking($id, $hours) {
    $kendaraan = Kendaraan::findOrFail($id);
    
    // Set a custom entry time
    $kendaraan->waktu_masuk = Carbon::now()->subHours($hours);
    $kendaraan->save();

    // Use the test controller to simulate exit
    $testController = new App\Http\Controllers\Parkir\TestController();
    $exitResult = $testController->simulasiKeluar($id);

    return $exitResult;
}

// Demonstrate exit for both vehicles
echo "Simulasi Keluar Kendaraan:\n\n";

// Exit motorcycle after 2 hours
echo "Kendaraan Motor:\n";
$motorExit = simulateParking(1, 2);
print_r($motorExit);

echo "\n\nKendaraan Mobil:\n";
// Exit car after 3 hours
$carExit = simulateParking(2, 3);
print_r($carExit);

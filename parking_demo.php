<?php
require 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Parkir\TarifParkir;
use App\Models\Parkir\SlotParkir;
use App\Models\Parkir\Kendaraan;

// Display Parking Rates
echo "Tarif Parkir:\n";
$tarifs = TarifParkir::all();
foreach ($tarifs as $tarif) {
    echo "Jenis: {$tarif->jenis_kendaraan}, Tarif/Jam: Rp. {$tarif->tarif_per_jam}\n";
}

// Display Parking Slots
echo "\nStatus Slot Parkir:\n";
$totalSlots = SlotParkir::count();
$emptySlots = SlotParkir::where('status', 'kosong')->count();
echo "Total Slot: {$totalSlots}\n";
echo "Slot Kosong: {$emptySlots}\n";

// Simulate Vehicle Entry
echo "\nSimulasi Masuk Kendaraan:\n";
$testController = new App\Http\Controllers\Parkir\TestController();
$motorEntry = $testController->simulasiMasuk('motor');
$mobilEntry = $testController->simulasiMasuk('mobil');

echo json_encode($motorEntry->getData(), JSON_PRETTY_PRINT) . "\n";
echo json_encode($mobilEntry->getData(), JSON_PRETTY_PRINT) . "\n";

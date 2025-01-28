<?php
require 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Parkir\SlotParkir;
use App\Models\Parkir\Kendaraan;
use App\Models\Parkir\TarifParkir;

// Function to simulate comprehensive parking scenarios
function demonstrateParkingScenarios() {
    // Scenario 1: Check Total Parking Slots
    $totalSlots = SlotParkir::count();
    $motorSlots = SlotParkir::where('jenis_kendaraan', 'motor')->count();
    $carSlots = SlotParkir::where('jenis_kendaraan', 'mobil')->count();
    
    echo "🅿️ Parking Slot Overview:\n";
    echo "Total Slots: {$totalSlots}\n";
    echo "Motor Slots: {$motorSlots}\n";
    echo "Car Slots: {$carSlots}\n\n";

    // Scenario 2: Available Slots
    $availableMotorSlots = SlotParkir::where('jenis_kendaraan', 'motor')
        ->where('status', 'kosong')
        ->get();
    
    $availableCarSlots = SlotParkir::where('jenis_kendaraan', 'mobil')
        ->where('status', 'kosong')
        ->get();
    
    echo "🚲 Available Motor Slots:\n";
    foreach ($availableMotorSlots->take(5) as $slot) {
        echo "- {$slot->nomor_slot}\n";
    }
    echo "Total Available Motor Slots: " . $availableMotorSlots->count() . "\n\n";

    echo "🚗 Available Car Slots:\n";
    foreach ($availableCarSlots->take(5) as $slot) {
        echo "- {$slot->nomor_slot}\n";
    }
    echo "Total Available Car Slots: " . $availableCarSlots->count() . "\n\n";

    // Scenario 3: Parking Rates
    $parkingRates = TarifParkir::all();
    echo "💰 Parking Rates:\n";
    foreach ($parkingRates as $rate) {
        echo "- {$rate->jenis_kendaraan}: Rp. " . 
             number_format($rate->tarif_per_jam, 0, ',', '.') . "/hour\n";
    }
    echo "\n";

    // Scenario 4: Simulate Filling Up Parking Slots
    $testController = new App\Http\Controllers\Parkir\TestController();
    
    echo "🚧 Parking Slot Occupation Simulation:\n";
    $occupiedSlots = [];

    // Fill up 10 motor slots
    for ($i = 1; $i <= 10; $i++) {
        $entry = $testController->simulasiMasuk('motor');
        $occupiedSlots[] = $entry->getData()->kendaraan;
    }

    // Fill up 5 car slots
    for ($i = 1; $i <= 5; $i++) {
        $entry = $testController->simulasiMasuk('mobil');
        $occupiedSlots[] = $entry->getData()->kendaraan;
    }

    echo "\n🏁 Occupation Summary:\n";
    echo "Occupied Motor Slots: " . 
        SlotParkir::where('jenis_kendaraan', 'motor')
            ->where('status', 'terisi')->count() . "\n";
    echo "Occupied Car Slots: " . 
        SlotParkir::where('jenis_kendaraan', 'mobil')
            ->where('status', 'terisi')->count() . "\n";

    return $occupiedSlots;
}

$occupiedVehicles = demonstrateParkingScenarios();

<?php
require __DIR__ . '/../vendor/autoload.php';

$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

function showDistinct($title, $rows) {
    echo "\n=== $title ===\n";
    if (count($rows) === 0) {
        echo "(no rows)\n";
        return;
    }
    foreach ($rows as $r) {
        $val = (string) ($r->val ?? '');
        $trim = trim($val);
        $hex = bin2hex($val);
        $len = strlen($val);
        $cnt = (int) ($r->cnt ?? 0);
        echo sprintf("value: '%s' | trim: '%s' | len: %d | hex: %s | count: %d\n", $val, $trim, $len, $hex, $cnt);
    }
}

// car_variants.fuel_type
$variantsFuel = DB::table('car_variants')
    ->selectRaw("fuel_type as val, COUNT(*) as cnt")
    ->whereNull('deleted_at')
    ->groupBy('fuel_type')
    ->orderBy('fuel_type')
    ->get();
showDistinct('car_variants.fuel_type (all)', $variantsFuel);

$variantsFuelActive = DB::table('car_variants')
    ->selectRaw("fuel_type as val, COUNT(*) as cnt")
    ->where('is_active', 1)
    ->whereNull('deleted_at')
    ->groupBy('fuel_type')
    ->orderBy('fuel_type')
    ->get();
showDistinct('car_variants.fuel_type (is_active=1)', $variantsFuelActive);

// car_variants.transmission
$variantsTrans = DB::table('car_variants')
    ->selectRaw("transmission as val, COUNT(*) as cnt")
    ->whereNull('deleted_at')
    ->groupBy('transmission')
    ->orderBy('transmission')
    ->get();
showDistinct('car_variants.transmission (all)', $variantsTrans);

$variantsTransActive = DB::table('car_variants')
    ->selectRaw("transmission as val, COUNT(*) as cnt")
    ->where('is_active', 1)
    ->whereNull('deleted_at')
    ->groupBy('transmission')
    ->orderBy('transmission')
    ->get();
showDistinct('car_variants.transmission (is_active=1)', $variantsTransActive);

// car_specifications (fuel_type, transmission, Hộp số)
$specFuel = DB::table('car_specifications')
    ->selectRaw("spec_value as val, COUNT(*) as cnt")
    ->where('spec_name', 'fuel_type')
    ->groupBy('spec_value')
    ->orderBy('spec_value')
    ->get();
showDistinct('car_specifications.fuel_type (spec_value)', $specFuel);

$specTrans = DB::table('car_specifications')
    ->selectRaw("spec_value as val, COUNT(*) as cnt")
    ->whereIn('spec_name', ['transmission', 'Hộp số'])
    ->groupBy('spec_value')
    ->orderBy('spec_value')
    ->get();
showDistinct('car_specifications.transmission/Hộp số (spec_value)', $specTrans);


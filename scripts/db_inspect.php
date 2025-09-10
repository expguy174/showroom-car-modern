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
        $len = strlen($val);
        $cnt = (int) ($r->cnt ?? 0);
        echo sprintf("value: '%s' | trim: '%s' | len: %d | count: %d\n", $val, $trim, $len, $cnt);
    }
}

// Orders status & payment_status
$ordersStatus = DB::table('orders')
    ->selectRaw("status as val, COUNT(*) as cnt")
    ->groupBy('status')
    ->orderBy('status')
    ->get();
showDistinct('orders.status', $ordersStatus);

$ordersPayStatus = DB::table('orders')
    ->selectRaw("payment_status as val, COUNT(*) as cnt")
    ->groupBy('payment_status')
    ->orderBy('payment_status')
    ->get();
showDistinct('orders.payment_status', $ordersPayStatus);


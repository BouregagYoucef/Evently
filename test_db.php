<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$invs = App\Models\Invitation::all();
echo "Total invitations: " . $invs->count() . "\n";
foreach ($invs as $inv) {
    echo "ID: {$inv->id} | UUID: {$inv->uuid} | Guest: {$inv->guest_id} | Status: {$inv->status}\n";
}
echo "\nGuests:\n";
$guests = App\Models\Guest::with('invitations')->get();
foreach ($guests as $guest) {
    echo "Guest ID: {$guest->id} | Name: {$guest->name} | Invs: " . $guest->invitations->pluck('status')->join(', ') . "\n";
    $latest = $guest->invitation;
    echo "  -> Primary Invitation Status: " . ($latest ? $latest->status : 'NONE') . "\n";
}

<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$guests = App\Models\Guest::with('invitations')->get();
$deleted = 0;
foreach ($guests as $guest) {
    if ($guest->invitations->count() > 1) {
        // Find if any is CONFIRMED
        $confirmed = $guest->invitations->where('status', 'CONFIRMED')->first();
        if ($confirmed) {
            // Delete all others
            foreach ($guest->invitations as $inv) {
                if ($inv->id !== $confirmed->id) {
                    $inv->delete();
                    $deleted++;
                }
            }
        } else {
            // Keep the latest one, delete others
            $latest = $guest->invitations->sortByDesc('id')->first();
            foreach ($guest->invitations as $inv) {
                if ($inv->id !== $latest->id) {
                    $inv->delete();
                    $deleted++;
                }
            }
        }
    }
}
echo "Deleted $deleted duplicate invitations.\n";

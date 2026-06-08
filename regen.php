<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

App\Models\Invitation::where('status', 'CONFIRMED')->get()->each(function($i) {
    App\Jobs\GenerateInvitationAssetsJob::dispatchSync($i);
});
echo "Done";

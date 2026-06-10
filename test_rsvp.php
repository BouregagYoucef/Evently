<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->bootstrap();

$inv = App\Models\Invitation::first();
if (!$inv) {
    echo "No invitation found.\n";
    exit;
}

$request = Illuminate\Http\Request::create('/i/' . $inv->uuid . '/rsvp', 'POST', [], [], [], ['CONTENT_TYPE' => 'application/json'], json_encode(['is_attending' => true]));
$request->headers->set('Accept', 'application/json');

$response = $kernel->handle($request);
file_put_contents('rsvp_debug.txt', "Status: " . $response->getStatusCode() . "\nContent: " . $response->getContent() . "\n");
echo "Done\n";

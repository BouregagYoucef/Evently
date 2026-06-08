<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$template = App\Models\Template::firstOrCreate(
    ['theme_identifier' => 'rose_whisper_arabic'], 
    ['name' => 'Rose Whisper (Arabic)', 'price' => 0.00, 'is_premium' => false, 'fields_schema' => []]
);
$host = App\Models\User::where('role', 'superadmin')->first();
$event = App\Models\Event::firstOrCreate(
    ['slug' => 'sarah-ahmed-wedding'], 
    [
        'host_id' => $host->id, 
        'template_id' => $template->id, 
        'title' => 'زفاف سارة وأحمد', 
        'event_datetime' => now()->addDays(10), 
        'location_name' => 'فندق الريتز كارلتون', 
        'location_address' => 'دبي، الإمارات', 
        'status' => 'PUBLISHED', 
        'content_data' => [
            'bride_name' => 'سارة', 
            'groom_name' => 'أحمد', 
            'greeting' => 'تتشرف عائلة العروسين بدعوتكم', 
            'date_formatted' => 'يوم الخميس، 15 أكتوبر'
        ]
    ]
);
$guest = App\Models\Guest::firstOrCreate(
    ['phone' => '+971501234567', 'event_id' => $event->id], 
    ['name' => 'محمد عبدالله']
);
$invitation = App\Models\Invitation::firstOrCreate(
    ['guest_id' => $guest->id, 'event_id' => $event->id], 
    ['uuid' => Illuminate\Support\Str::uuid(), 'status' => 'PENDING']
);
echo $event->slug . '|' . $invitation->uuid;

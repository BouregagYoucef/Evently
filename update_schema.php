<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$template = App\Models\Template::where('theme_identifier', 'rose_whisper_arabic')->first();
if ($template) {
    $template->fields_schema = [
        ['name' => 'bride_name', 'label' => 'اسم العروس', 'type' => 'text', 'required' => true],
        ['name' => 'groom_name', 'label' => 'اسم العريس', 'type' => 'text', 'required' => true],
        ['name' => 'greeting', 'label' => 'رسالة الترحيب', 'type' => 'text', 'required' => false],
        ['name' => 'date_formatted', 'label' => 'صيغة التاريخ المكتوبة', 'type' => 'text', 'required' => false],
        ['name' => 'location_address', 'label' => 'وصف الموقع والتفاصيل', 'type' => 'textarea', 'required' => false],
        ['name' => 'cover_image', 'label' => 'رابط صورة الخلفية', 'type' => 'text', 'required' => false],
    ];
    $template->save();
    echo "Schema updated successfully!\n";
} else {
    echo "Template not found.\n";
}

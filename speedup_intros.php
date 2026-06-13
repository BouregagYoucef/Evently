<?php

$dir = __DIR__ . '/resources/views/themes';
$themes = glob($dir . '/*', GLOB_ONLYDIR);

foreach ($themes as $theme) {
    $file = $theme . '/index.blade.php';
    if (!file_exists($file)) continue;
    
    $content = file_get_contents($file);
    $original = $content;
    
    // ONLY reduce the setTimeout delay before startExperience is called
    // We match: setTimeout(() => this.startExperience(), <any_number>);
    $content = preg_replace('/setTimeout\(\(\)\s*=>\s*this\.startExperience\(\),\s*\d+\);/', 'setTimeout(() => this.startExperience(), 100);', $content);
    
    if ($content !== $original) {
        file_put_contents($file, $content);
        echo "Updated setTimeout in: " . basename($theme) . "\n";
    }
}

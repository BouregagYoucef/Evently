<?php

$themes = [
    'resources/views/themes/rose_whisper_arabic/index.blade.php',
    'resources/views/themes/rose_whisper_english/index.blade.php',
    'resources/views/themes/rose_whisper_francais/index.blade.php'
];

foreach ($themes as $file) {
    $path = __DIR__ . '/' . $file;
    if (!file_exists($path)) continue;
    
    $content = file_get_contents($path);
    $original = $content;
    
    // Fix the 6 second delay in setupPrelude
    $content = str_replace('delay: 6', 'delay: 1', $content);
    
    // Fix the 2 second delay after the text appears
    $content = str_replace('delay: 2, ease', 'delay: 1, ease', $content);
    
    // Speed up the petal spawning to match the new shorter prelude
    $content = str_replace('setTimeout(() => this.spawnPetal(), 1000); // 1st', 'setTimeout(() => this.spawnPetal(), 100); // 1st', $content);
    $content = str_replace('setTimeout(() => this.spawnPetal(), 3000); // 2nd', 'setTimeout(() => this.spawnPetal(), 400); // 2nd', $content);
    $content = str_replace('setTimeout(() => this.spawnPetal(), 4500); // 3rd', 'setTimeout(() => this.spawnPetal(), 800); // 3rd', $content);
    
    if ($content !== $original) {
        file_put_contents($path, $content);
        echo "Updated: " . $file . "\n";
    }
}

<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use App\Models\Template;
use Illuminate\Support\Str;

class ImportPythonTemplates extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'evently:import-python-templates';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Imports, compiles, and seeds Django/Python templates into Evently';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $sourceDir = 'C:\\Users\\Youcef\\Documents\\Python\\Elite_Invitations\\apps\\templates_app\\templates\\themes';
        
        if (!File::exists($sourceDir)) {
            $this->error("Source directory not found: $sourceDir");
            return;
        }

        $directories = File::directories($sourceDir);
        $importedCount = 0;

        foreach ($directories as $dir) {
            $identifier = basename($dir);
            $indexPath = $dir . '\\index.html';

            if (!File::exists($indexPath)) {
                $this->warn("Skipping $identifier: index.html not found.");
                continue;
            }

            $this->info("Processing Template: $identifier");

            $htmlContent = File::get($indexPath);

            // 1. Compile Django syntax to Blade
            $compiledHtml = $htmlContent;

            // json_script
            $compiledHtml = preg_replace(
                '/\{\{\s*content_data\|json_script:[\'"]([^\'"]+)[\'"]\s*\}\}/',
                '<script id="$1" type="application/json">@json($event->content_data)</script>',
                $compiledHtml
            );

            // date filters
            $compiledHtml = preg_replace(
                '/\{\{\s*event\.([a-zA-Z0-9_]+)\|date:[\'"]([^\'"]+)[\'"]\s*\}\}/',
                '{{ \Carbon\Carbon::parse($event->$1)->translatedFormat(\'$2\') }}',
                $compiledHtml
            );

            // isoformat
            $compiledHtml = preg_replace('/\{\{\s*event\.event_datetime\.isoformat\s*\}\}/', '{{ \Carbon\Carbon::parse($event->event_datetime)->toIso8601String() }}', $compiledHtml);

            // event variable access
            $compiledHtml = preg_replace('/\{\{\s*event\.([a-zA-Z0-9_]+)\s*\}\}/', '{{ $event->$1 }}', $compiledHtml);

            // guest variable access
            $compiledHtml = preg_replace('/\{\{\s*guest\.([a-zA-Z0-9_]+)\s*\}\}/', '{{ $guest->$1 ?? \'\' }}', $compiledHtml);

            // if/else tags
            $compiledHtml = preg_replace('/\{%\s*if\s+([a-zA-Z0-9_]+)\s*%\}/', '@if(isset($$1) && $$1)', $compiledHtml);
            $compiledHtml = preg_replace('/\{%\s*else\s*%\}/', '@else', $compiledHtml);
            $compiledHtml = preg_replace('/\{%\s*endif\s*%\}/', '@endif', $compiledHtml);

            // rsvp_form
            $compiledHtml = preg_replace(
                '/\{\{\s*rsvp_form\s*\}\}/',
                "@include('components.evently-rsvp', ['event' => \$event, 'isPublic' => \$isPublic ?? false, 'invitation' => \$invitation ?? null, 'guest' => \$guest ?? null])",
                $compiledHtml
            );

            // 2. Generate fields_schema automatically
            $schema = [];
            $seenFields = [];

            // Find all data.xxx or contentData.xxx usage in Alpine
            preg_match_all('/(?:data|contentData)\.([a-zA-Z0-9_]+)/', $compiledHtml, $matches);
            
            if (!empty($matches[1])) {
                foreach ($matches[1] as $fieldName) {
                    if (in_array($fieldName, $seenFields)) continue;
                    
                    $seenFields[] = $fieldName;
                    
                    // Infer type
                    $type = 'text';
                    if (str_contains($fieldName, 'image') || str_contains($fieldName, 'logo')) {
                        $type = 'image';
                    } elseif (str_contains($fieldName, 'gallery')) {
                        $type = 'gallery';
                    } elseif (str_contains($fieldName, 'content') || str_contains($fieldName, 'description') || str_contains($fieldName, 'message')) {
                        $type = 'textarea';
                    } elseif (str_contains($fieldName, 'date')) {
                        $type = 'date';
                    }

                    $schema[] = [
                        'name' => $fieldName,
                        'label' => Str::headline($fieldName),
                        'type' => $type,
                        'required' => false,
                    ];
                }
            }

            // 3. Save to DB
            $template = Template::firstOrNew(['theme_identifier' => $identifier]);
            $template->name = Str::headline($identifier);
            $template->fields_schema = $schema;
            $template->is_active = true;
            $template->save();

            // 4. Save Blade View
            $themeDir = resource_path("views/themes/{$identifier}");
            if (!File::exists($themeDir)) {
                File::makeDirectory($themeDir, 0755, true);
            }
            File::put($themeDir . '/index.blade.php', $compiledHtml);

            $importedCount++;
            $this->info("-> Successfully imported and compiled to {$themeDir}/index.blade.php");
        }

        $this->info("Completed! Imported $importedCount templates automatically.");
    }
}

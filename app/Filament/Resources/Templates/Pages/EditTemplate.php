<?php

namespace App\Filament\Resources\Templates\Pages;

use App\Filament\Resources\Templates\TemplateResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditTemplate extends EditRecord
{
    protected static string $resource = TemplateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    public ?string $htmlContent = null;

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $this->htmlContent = $this->data['html_content'] ?? null;
        return $data;
    }

    protected function afterSave(): void
    {
        if ($this->htmlContent) {
            $identifier = $this->record->theme_identifier;
            $path = resource_path("views/themes/{$identifier}");
            
            if (!file_exists($path)) {
                mkdir($path, 0755, true);
            }

            // 1. Compile Django/Python tags to Laravel JSON directives
            $compiledHtml = preg_replace(
                '/\{\{\s*content_data\|json_script:[\'"]([^\'"]+)[\'"]\s*\}\}/',
                '<script id="$1" type="application/json">@json($event->content_data)</script>',
                $this->htmlContent
            );

            // 2. Fix Django date filters: {{ event.event_datetime|date:"l, F j, Y" }}
            $compiledHtml = preg_replace(
                '/\{\{\s*event\.([a-zA-Z0-9_]+)\|date:[\'"]([^\'"]+)[\'"]\s*\}\}/',
                '{{ \Carbon\Carbon::parse($event->$1)->translatedFormat(\'$2\') }}',
                $compiledHtml
            );

            // 2.5 Fix Django ISO date format: {{ event.event_datetime.isoformat }}
            $compiledHtml = preg_replace('/\{\{\s*event\.event_datetime\.isoformat\s*\}\}/', '{{ \Carbon\Carbon::parse($event->event_datetime)->toIso8601String() }}', $compiledHtml);

            // 3. Fix Django variable access: {{ event.title }} -> {{ $event->title }}
            $compiledHtml = preg_replace('/\{\{\s*event\.([a-zA-Z0-9_]+)\s*\}\}/', '{{ $event->$1 }}', $compiledHtml);

            // 4. Fix Django guest variable access: {{ guest.name }} -> {{ $guest->name ?? '' }}
            $compiledHtml = preg_replace('/\{\{\s*guest\.([a-zA-Z0-9_]+)\s*\}\}/', '{{ $guest->$1 ?? \'\' }}', $compiledHtml);

            // 5. Fix Django if statements: {% if guest %} -> @if(isset($guest) && $guest)
            $compiledHtml = preg_replace('/\{%\s*if\s+([a-zA-Z0-9_]+)\s*%\}/', '@if(isset($$1) && $$1)', $compiledHtml);
            $compiledHtml = preg_replace('/\{%\s*else\s*%\}/', '@else', $compiledHtml);
            $compiledHtml = preg_replace('/\{%\s*endif\s*%\}/', '@endif', $compiledHtml);

            // 6. Fix RSVP Form Injector: {{ rsvp_form }}
            $compiledHtml = preg_replace(
                '/\{\{\s*rsvp_form\s*\}\}/',
                "@include('components.evently-rsvp', ['event' => \$event, 'isPublic' => \$isPublic ?? false, 'invitation' => \$invitation ?? null, 'guest' => \$guest ?? null])",
                $compiledHtml
            );

            file_put_contents($path . '/index.blade.php', $compiledHtml);
        }
    }
}

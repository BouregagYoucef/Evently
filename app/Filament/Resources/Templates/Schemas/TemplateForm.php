<?php

namespace App\Filament\Resources\Templates\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class TemplateForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Schemas\Components\Section::make('Designer Guide')
                    ->description('How to build a Headless Theme')
                    ->schema([
                        \Filament\Forms\Components\Placeholder::make('guide')
                            ->label('')
                            ->content(new \Illuminate\Support\HtmlString('
                                <div class="prose dark:prose-invert text-sm">
                                    <p><strong>1. View Path:</strong> Create your blade file at <code>resources/views/themes/{theme_identifier}/index.blade.php</code>.</p>
                                    <p><strong>2. Data Injection:</strong> Inside your blade file, inject the dynamic content using: <code>&lt;script id="content-data" type="application/json"&gt;{!! $event-&gt;content_data !!}&lt;/script&gt;</code>.</p>
                                    <p><strong>3. JS/CSS:</strong> Use standard Vite imports for your GSAP/Tailwind logic.</p>
                                    <p><strong>4. Schema:</strong> Define the dynamic fields below so Hosts can fill them out in the Wizard.</p>
                                </div>
                            ')),
                    ]),
                \Filament\Forms\Components\TextInput::make('name')
                    ->required(),
                \Filament\Forms\Components\TextInput::make('theme_identifier')
                    ->helperText('e.g. arabic_cinematic_wedding')
                    ->required(),
                \Filament\Forms\Components\FileUpload::make('preview_image')
                    ->image()
                    ->directory('templates/previews'),
                \Filament\Forms\Components\Textarea::make('html_content')
                    ->label('HTML / Blade Code')
                    ->helperText('Paste your HTML code here. Variables will be injected automatically.')
                    ->rows(25)
                    ->extraAttributes(['style' => 'font-family: monospace; direction: ltr;'])
                    ->formatStateUsing(function ($record) {
                        if (!$record || !$record->theme_identifier) return '';
                        $path = resource_path("views/themes/{$record->theme_identifier}/index.blade.php");
                        return file_exists($path) ? file_get_contents($path) : '';
                    })
                    ->dehydrated(false),
                \Filament\Forms\Components\Textarea::make('fields_schema')
                    ->label('Fields Schema (JSON Array)')
                    ->helperText('Paste your fields schema directly in JSON format. Example: [{"name": "story_title", "type": "text", "label": "Story Title"}]')
                    ->rows(15)
                    ->extraAttributes(['style' => 'font-family: monospace; direction: ltr;'])
                    ->formatStateUsing(fn ($state) => is_array($state) ? json_encode($state, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) : $state)
                    ->dehydrateStateUsing(function ($state) {
                        if (empty($state)) return [];
                        $decoded = json_decode($state, true);
                        return is_array($decoded) ? $decoded : [];
                    })
                    ->columnSpanFull(),
                Toggle::make('is_active')
                    ->required(),
            ]);
    }
}

<?php

namespace App\Filament\Host\Resources\Events\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class EventForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Schemas\Components\Wizard::make([
                    \Filament\Schemas\Components\Wizard\Step::make('Basic Details')
                        ->description('Set up your event')
                        ->schema([
                            \Filament\Forms\Components\Hidden::make('host_id')->default(fn () => auth()->id()),
                            \Filament\Forms\Components\TextInput::make('title')->required(),
                            \Filament\Forms\Components\TextInput::make('slug')
                                ->required(),
                            \Filament\Forms\Components\DateTimePicker::make('event_datetime')->required(),
                            \Filament\Forms\Components\TextInput::make('location_name')->required(),
                            \Filament\Forms\Components\TextInput::make('max_guests')
                                ->required()
                                ->numeric()
                                ->maxValue(fn () => auth()->user()->max_guests_per_event)
                                ->default(fn () => auth()->user()->max_guests_per_event),
                            \Filament\Forms\Components\Select::make('status')
                                ->options(['DRAFT' => 'Draft', 'PUBLISHED' => 'Published', 'ARCHIVED' => 'Archived'])
                                ->default('DRAFT')
                                ->required(),
                        ]),
                    \Filament\Schemas\Components\Wizard\Step::make('Select Template')
                        ->description('Choose a cinematic theme')
                        ->schema([
                            \Filament\Forms\Components\Select::make('template_id')
                                ->relationship('template', 'name')
                                ->required()
                                ->live()
                                ->suffixAction(
                                    \Filament\Actions\Action::make('preview_template')
                                        ->icon('heroicon-o-eye')
                                        ->url(function (\Filament\Schemas\Components\Utilities\Get $get) {
                                            $templateId = $get('template_id');
                                            return $templateId ? route('template.preview', $templateId) : null;
                                        })
                                        ->openUrlInNewTab()
                                        ->visible(fn (\Filament\Schemas\Components\Utilities\Get $get) => filled($get('template_id')))
                                )
                                ->afterStateUpdated(fn (callable $set) => $set('content_data', [])),
                            \Filament\Forms\Components\Placeholder::make('template_preview')
                                ->label('Live Preview')
                                ->content(function (\Filament\Schemas\Components\Utilities\Get $get) {
                                    $templateId = $get('template_id');
                                    if (! $templateId) return 'No template selected.';
                                    
                                    $previewUrl = route('template.preview', $templateId);
                                    return new \Illuminate\Support\HtmlString("
                                        <div class='mx-auto overflow-hidden shadow-2xl relative bg-gray-900' style='width: 320px; height: 570px; border-radius: 20px; border: 4px solid #1f2937;'>
                                            <iframe src='{$previewUrl}' style='width: 100%; height: 100%; border: none; pointer-events: none;'></iframe>
                                        </div>
                                    ");
                                }),
                        ]),
                    \Filament\Schemas\Components\Wizard\Step::make('Headless Theme Setup')
                        ->description('Enter dynamic content')
                        ->schema(function (\Filament\Schemas\Components\Utilities\Get $get) {
                            $templateId = $get('template_id');
                            if (! $templateId) {
                                return [
                                    \Filament\Forms\Components\Placeholder::make('info')
                                        ->label('')
                                        ->content('Please select a template first.'),
                                ];
                            }

                            $template = \App\Models\Template::find($templateId);
                            
                            if (! $template || empty($template->fields_schema)) {
                                return [
                                    \Filament\Forms\Components\KeyValue::make('content_data')
                                        ->label('Dynamic Content Data')
                                        ->keyLabel('Variable Name')
                                        ->valueLabel('Content')
                                ];
                            }

                            $schemaFields = is_string($template->fields_schema) 
                                ? json_decode($template->fields_schema, true) 
                                : $template->fields_schema;
                                
                            if (!is_array($schemaFields)) {
                                $schemaFields = [];
                            }

                            $fields = [];
                            foreach ($schemaFields as $field) {
                                $component = match ($field['type'] ?? 'text') {
                                    'textarea' => \Filament\Forms\Components\Textarea::make('content_data.' . $field['name']),
                                    'date' => \Filament\Forms\Components\DatePicker::make('content_data.' . $field['name']),
                                    'color' => \Filament\Forms\Components\ColorPicker::make('content_data.' . $field['name']),
                                    'image' => \Filament\Forms\Components\FileUpload::make('content_data.' . $field['name'])->image()->directory('events/images')->imageEditor(),
                                    'gallery' => \Filament\Forms\Components\FileUpload::make('content_data.' . $field['name'])->image()->multiple()->directory('events/gallery')->reorderable()->imageEditor(),
                                    default => \Filament\Forms\Components\TextInput::make('content_data.' . $field['name']),
                                };

                                $fields[] = $component
                                    ->label($field['label'] ?? ucfirst(str_replace('_', ' ', $field['name'])))
                                    ->required($field['required'] ?? false);
                            }

                            return $fields;
                        }),
                ])->columnSpan('full')
            ]);
    }
}

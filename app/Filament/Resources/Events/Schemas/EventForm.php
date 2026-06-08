<?php

namespace App\Filament\Resources\Events\Schemas;

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
                Select::make('host_id')
                    ->relationship('host', 'name')
                    ->required(),
                Select::make('template_id')
                    ->relationship('template', 'name')
                    ->required()
                    ->live(),
                TextInput::make('slug')
                    ->required(),
                TextInput::make('title')
                    ->required(),
                DateTimePicker::make('event_datetime')
                    ->required(),
                TextInput::make('location_name')
                    ->required(),
                TextInput::make('max_guests')
                    ->required()
                    ->numeric()
                    ->default(500),
                Select::make('status')
                    ->options(['DRAFT' => 'Draft', 'PUBLISHED' => 'Published', 'ARCHIVED' => 'Archived'])
                    ->default('DRAFT')
                    ->required(),
                
                \Filament\Schemas\Components\Section::make('Theme Settings')
                    ->description('Dynamic fields based on your selected template')
                    ->schema(function (\Filament\Schemas\Components\Utilities\Get $get) {
                        $templateId = $get('template_id');
                        if (! $templateId) {
                            return [
                                \Filament\Forms\Components\Placeholder::make('info')
                                    ->label('')
                                    ->content('Please select a template to view theme settings.'),
                            ];
                        }

                        $template = \App\Models\Template::find($templateId);
                        
                        // If no custom schema defined, fallback to a Key-Value editor
                        if (! $template || empty($template->fields_schema)) {
                            return [
                                \Filament\Forms\Components\KeyValue::make('content_data')
                                    ->label('Dynamic Content Data')
                                    ->keyLabel('Variable Name')
                                    ->valueLabel('Content')
                            ];
                        }

                        $fields = [];
                        foreach ($template->fields_schema as $field) {
                            $component = match ($field['type'] ?? 'text') {
                                'textarea' => \Filament\Forms\Components\Textarea::make('content_data.' . $field['name']),
                                'date' => \Filament\Forms\Components\DatePicker::make('content_data.' . $field['name']),
                                'color' => \Filament\Forms\Components\ColorPicker::make('content_data.' . $field['name']),
                                default => \Filament\Forms\Components\TextInput::make('content_data.' . $field['name']),
                            };

                            $fields[] = $component
                                ->label($field['label'] ?? ucfirst(str_replace('_', ' ', $field['name'])))
                                ->required($field['required'] ?? false);
                        }

                        return $fields;
                    }),
            ]);
    }
}

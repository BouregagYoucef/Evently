<?php

namespace App\Filament\Host\Resources\Guests\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class GuestForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('event_id')
                    ->relationship('event', 'title', function ($query) {
                        return $query->where('host_id', auth()->id());
                    })
                    ->required()
                    ->rules([
                        function () {
                            return function (string $attribute, $value, \Closure $fail) {
                                $event = \App\Models\Event::with('host')->find($value);
                                if ($event && $event->guests()->count() >= $event->host->max_guests_per_event) {
                                    $fail("This event has reached the maximum limit of {$event->host->max_guests_per_event} guests.");
                                }
                            };
                        },
                    ]),
                TextInput::make('name')
                    ->required(),
                TextInput::make('phone')
                    ->tel(),
                TextInput::make('email')
                    ->label('Email address')
                    ->email(),
                Select::make('type')
                    ->options(['VIP' => 'V i p', 'REGULAR' => 'R e g u l a r'])
                    ->default('REGULAR')
                    ->required(),
                TextInput::make('companions_count')
                    ->required()
                    ->numeric()
                    ->default(0),
            ]);
    }
}

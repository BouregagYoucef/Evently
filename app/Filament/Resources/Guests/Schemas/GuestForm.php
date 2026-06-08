<?php

namespace App\Filament\Resources\Guests\Schemas;

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
                    ->relationship('event', 'title')
                    ->required(),
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

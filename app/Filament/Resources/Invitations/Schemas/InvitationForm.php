<?php

namespace App\Filament\Resources\Invitations\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class InvitationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('uuid')
                    ->label('UUID')
                    ->required(),
                Select::make('event_id')
                    ->relationship('event', 'title')
                    ->required(),
                Select::make('guest_id')
                    ->relationship('guest', 'name')
                    ->required(),
                Select::make('status')
                    ->options([
            'PENDING' => 'P e n d i n g',
            'OPENED' => 'O p e n e d',
            'CONFIRMED' => 'C o n f i r m e d',
            'DECLINED' => 'D e c l i n e d',
            'CHECKED_IN' => 'C h e c k e d  i n',
        ])
                    ->default('PENDING')
                    ->required(),
                TextInput::make('qr_code_path'),
                TextInput::make('pdf_path'),
                DateTimePicker::make('opened_at'),
                DateTimePicker::make('confirmed_at'),
                DateTimePicker::make('declined_at'),
            ]);
    }
}

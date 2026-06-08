<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('البيانات الشخصية (Personal Information)')
                    ->description('تعديل الحساب وكلمة المرور.')
                    ->columns(2)
                    ->schema([
                        TextInput::make('name')
                            ->required(),
                        TextInput::make('email')
                            ->label('Email address')
                            ->email()
                            ->required(),
                        DateTimePicker::make('email_verified_at'),
                        TextInput::make('password')
                            ->password()
                            ->dehydrateStateUsing(fn ($state) => \Illuminate\Support\Facades\Hash::make($state))
                            ->dehydrated(fn ($state) => filled($state))
                            ->required(fn (string $context): bool => $context === 'create')
                            ->revealable(),
                    ]),
                
                Section::make('الإعدادات والصلاحيات (Settings & Quotas)')
                    ->description('صلاحيات المستخدم والحدود المسموحة.')
                    ->columns(2)
                    ->schema([
                        Select::make('role')
                            ->options(['superadmin' => 'Superadmin', 'host' => 'Host'])
                            ->default('host')
                            ->required(),
                        TextInput::make('max_events')
                            ->label('Max Events')
                            ->numeric()
                            ->default(3)
                            ->required(),
                        TextInput::make('max_guests_per_event')
                            ->label('Max Guests Per Event')
                            ->numeric()
                            ->default(100)
                            ->required(),
                        Toggle::make('is_approved')
                            ->columnSpanFull()
                            ->required(),
                    ]),
            ]);
    }
}

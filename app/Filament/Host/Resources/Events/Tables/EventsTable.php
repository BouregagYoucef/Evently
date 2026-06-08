<?php

namespace App\Filament\Host\Resources\Events\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class EventsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('host.name')
                    ->searchable(),
                TextColumn::make('template.name')
                    ->searchable(),
                TextColumn::make('slug')
                    ->searchable(),
                TextColumn::make('title')
                    ->searchable(),
                TextColumn::make('event_datetime')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('location_name')
                    ->searchable(),
                TextColumn::make('max_guests')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('status')
                    ->badge(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordUrl(fn (\App\Models\Event $record): string => \App\Filament\Host\Resources\Events\EventResource::getUrl('manage', ['record' => $record]))
            ->recordActions([
                \Filament\Actions\Action::make('manage')
                    ->label('Manage')
                    ->icon('heroicon-o-cog-6-tooth')
                    ->url(fn (\App\Models\Event $record): string => \App\Filament\Host\Resources\Events\EventResource::getUrl('manage', ['record' => $record])),
                \Filament\Actions\Action::make('scan')
                    ->label('Scan Tickets')
                    ->icon('heroicon-o-qr-code')
                    ->color('success')
                    ->url(fn (\App\Models\Event $record): string => \App\Filament\Host\Resources\Events\EventResource::getUrl('scan', ['record' => $record])),
                \Filament\Actions\EditAction::make(),
                \Filament\Actions\DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}

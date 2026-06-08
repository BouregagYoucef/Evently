<?php

namespace App\Filament\Host\Resources\Guests\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class GuestsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('event.title')
                    ->searchable(),
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('phone')
                    ->searchable(),
                TextColumn::make('email')
                    ->label('Email address')
                    ->searchable(),
                TextColumn::make('invitation.status')
                    ->label('RSVP Status')
                    ->badge()
                    ->color(fn (?string $state): string => match ($state) {
                        'PENDING' => 'warning',
                        'OPENED' => 'info',
                        'CONFIRMED' => 'success',
                        'DECLINED' => 'danger',
                        'CHECKED_IN' => 'success',
                        default => 'gray',
                    }),
                TextColumn::make('type')
                    ->badge(),
                TextColumn::make('companions_count')
                    ->numeric()
                    ->sortable(),
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
            ->recordActions([
                \Filament\Tables\Actions\EditAction::make(),
                \Filament\Tables\Actions\Action::make('copyLink')
                    ->label('Copy Link')
                    ->icon('heroicon-o-link')
                    ->color('gray')
                    ->action(function () {})
                    ->extraAttributes(function ($record) {
                        if($record->invitation) {
                            $url = url('/i/' . $record->invitation->uuid);
                            return [
                                'x-on:click' => "window.navigator.clipboard.writeText('$url'); \$tooltip('Link copied to clipboard') ; event.preventDefault()",
                            ];
                        }
                        return [];
                    }),
                \Filament\Tables\Actions\Action::make('sendEmail')
                    ->label('Send Invite')
                    ->icon('heroicon-o-envelope')
                    ->color('primary')
                    ->requiresConfirmation()
                    ->action(function ($record) {
                        if ($record->email && $record->invitation) {
                            \Illuminate\Support\Facades\Mail::to($record->email)->send(new \App\Mail\InvitationMail($record->invitation));
                            \Filament\Notifications\Notification::make()
                                ->title('Invitation sent successfully')
                                ->success()
                                ->send();
                        } else {
                            \Filament\Notifications\Notification::make()
                                ->title('Guest has no email or missing invitation')
                                ->danger()
                                ->send();
                        }
                    }),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}

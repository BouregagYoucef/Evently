<?php

namespace App\Filament\Host\Resources\Events\RelationManagers;

use App\Filament\Host\Resources\Guests\GuestResource;
use App\Jobs\ProcessCsvImportJob;
use Filament\Forms;
use Filament\Forms\Form; // Can be removed
use Filament\Schemas\Schema;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Notifications\Notification;

class GuestsRelationManager extends RelationManager
{
    protected static string $relationship = 'guests';

    protected static ?string $title = 'Guest List';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('phone')
                    ->tel()
                    ->maxLength(255),
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->maxLength(255),
                Forms\Components\Select::make('type')
                    ->options([
                        'VIP' => 'VIP',
                        'REGULAR' => 'Regular',
                        'PUBLIC' => 'Public (Joined via Link)',
                    ])
                    ->default('REGULAR')
                    ->required(),
                Forms\Components\TextInput::make('companions_count')
                    ->numeric()
                    ->default(0)
                    ->required(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->modifyQueryUsing(fn ($query) => $query->with(['invitation', 'invitations']))
            ->columns([
                Tables\Columns\TextColumn::make('name')->searchable(),
                Tables\Columns\TextColumn::make('phone')->searchable(),
                Tables\Columns\TextColumn::make('type')->badge(),
                Tables\Columns\TextColumn::make('invitation.status')
                    ->label('RSVP Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'PENDING' => 'gray',
                        'OPENED' => 'info',
                        'CONFIRMED' => 'success',
                        'DECLINED' => 'danger',
                        'CHECKED_IN' => 'primary',
                        default => 'gray',
                    }),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                \Filament\Actions\Action::make('add_guest_manually')
                    ->label('Add Guest Manually')
                    ->icon('heroicon-o-plus')
                    ->color('primary')
                    ->form([
                        Forms\Components\TextInput::make('name')->required(),
                        Forms\Components\TextInput::make('phone')->tel(),
                        Forms\Components\TextInput::make('email')->email(),
                        Forms\Components\Select::make('type')
                            ->options([
                                'VIP' => 'VIP',
                                'REGULAR' => 'Regular',
                            ])->default('REGULAR')->required(),
                        Forms\Components\TextInput::make('companions_count')->numeric()->default(0)->required(),
                    ])
                    ->action(function (array $data, RelationManager $livewire) {
                        $guest = $livewire->getOwnerRecord()->guests()->create($data);
                        Notification::make()->title('Guest added successfully')->success()->send();
                    }),
                \Filament\Actions\Action::make('import_csv')
                    ->label('Import CSV')
                    ->icon('heroicon-o-arrow-up-tray')
                    ->color('gray')
                    ->form([
                        Forms\Components\FileUpload::make('csv_file')
                            ->label('CSV File')
                            ->acceptedFileTypes(['text/csv', 'application/csv'])
                            ->disk('local')
                            ->directory('imports')
                            ->required(),
                    ])
                    ->action(function (array $data, RelationManager $livewire) {
                        $eventId = $livewire->getOwnerRecord()->id;
                        
                        \App\Models\AuditLog::create([
                            'user_id' => auth()->id(),
                            'event_id' => $eventId,
                            'action' => 'IMPORTED_CSV',
                            'description' => "Triggered background CSV import from relation manager",
                            'ip_address' => request()->ip(),
                        ]);

                        ProcessCsvImportJob::dispatch($eventId, $data['csv_file']);

                        Notification::make()
                            ->title('Import Started')
                            ->body('Your CSV file is being processed in the background.')
                            ->success()
                            ->send();
                    }),
            ])
            ->actions([
                \Filament\Actions\Action::make('copy_link')
                    ->label('Copy Link')
                    ->icon('heroicon-o-clipboard-document-list')
                    ->color('gray')
                    ->url(fn () => '#')
                    ->extraAttributes(function ($record) {
                        $invitation = $record->invitations->first();
                        if (!$invitation) {
                            return [
                                'x-on:click.prevent' => "new FilamentNotification().title('Error').body('This guest does not have an active invitation link yet.').danger().send();"
                            ];
                        }
                        
                        $url = url('/i/' . $invitation->uuid);
                        return [
                            'x-on:click.prevent' => "
                                const text = '{$url}';
                                if (navigator.clipboard && window.isSecureContext) {
                                    navigator.clipboard.writeText(text);
                                } else {
                                    const textArea = document.createElement('textarea');
                                    textArea.value = text;
                                    textArea.style.position = 'fixed';
                                    textArea.style.left = '-999999px';
                                    document.body.appendChild(textArea);
                                    textArea.focus();
                                    textArea.select();
                                    try { document.execCommand('copy'); } catch (err) {}
                                    document.body.removeChild(textArea);
                                }
                                new FilamentNotification().title('Link Copied').body('The private invitation link has been copied to your clipboard.').success().send();
                            "
                        ];
                    }),
                \Filament\Actions\Action::make('whatsapp')
                    ->label('WhatsApp')
                    ->icon('heroicon-o-chat-bubble-oval-left-ellipsis')
                    ->color('success')
                    ->url(function ($record, RelationManager $livewire) {
                        $invitation = $record->invitations->first();
                        if (!$record->phone || !$invitation) return null;
                        
                        $link = url('/i/' . $invitation->uuid);
                        $message = "مرحباً {$record->name}، نتشرف بدعوتكم لحضور مناسبتنا. تفاصيل الدعوة وتأكيد الحضور عبر الرابط: {$link}";
                        return "https://wa.me/" . preg_replace('/[^0-9]/', '', $record->phone) . "?text=" . urlencode($message);
                    })
                    ->openUrlInNewTab()
                    ->visible(fn ($record) => !empty($record->phone)),
                \Filament\Actions\Action::make('send_email')
                    ->label('Email')
                    ->icon('heroicon-o-envelope')
                    ->color('primary')
                    ->action(function ($record) {
                        $invitation = $record->invitations->first();
                        if ($record->email && $invitation) {
                            \Illuminate\Support\Facades\Mail::to($record->email)->queue(new \App\Mail\TicketMail($invitation));
                            Notification::make()->title('Email Queued')->success()->send();
                        }
                    })
                    ->visible(fn ($record) => !empty($record->email)),
                \Filament\Actions\EditAction::make(),
                \Filament\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                \Filament\Actions\BulkActionGroup::make([
                    \Filament\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}

<?php

namespace App\Filament\Host\Resources\Guests\Pages;

use App\Filament\Host\Resources\Guests\GuestResource;
use App\Jobs\ProcessCsvImportJob;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;

class ListGuests extends ListRecords
{
    protected static string $resource = GuestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('import_csv')
                ->label('Import CSV')
                ->icon('heroicon-o-arrow-up-tray')
                ->color('gray')
                ->form([
                    Select::make('event_id')
                        ->label('Select Event')
                        ->options(function () {
                            return auth()->user()->events()->pluck('title', 'id');
                        })
                        ->required(),
                    FileUpload::make('csv_file')
                        ->label('CSV File')
                        ->acceptedFileTypes(['text/csv', 'application/csv'])
                        ->disk('local')
                        ->directory('imports')
                        ->required(),
                ])
                ->action(function (array $data) {
                    // Log Action
                    \App\Models\AuditLog::create([
                        'user_id' => auth()->id(),
                        'event_id' => $data['event_id'],
                        'action' => 'IMPORTED_CSV',
                        'description' => "Triggered background CSV import",
                        'ip_address' => request()->ip(),
                    ]);

                    // Dispatch Job
                    ProcessCsvImportJob::dispatch($data['event_id'], $data['csv_file']);

                    Notification::make()
                        ->title('Import Started')
                        ->body('Your CSV file is being processed in the background. Guests will appear shortly.')
                        ->success()
                        ->send();
                }),
            CreateAction::make(),
        ];
    }
}

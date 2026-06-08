<?php

namespace App\Filament\Host\Resources\Events\Pages;

use App\Filament\Host\Resources\Events\EventResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Placeholder;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
use Illuminate\Support\HtmlString;

class ManageEvent extends ViewRecord
{
    protected static string $resource = EventResource::class;

    public function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make(3)
                    ->schema([
                        Section::make('Event Overview')
                            ->schema([
                                Placeholder::make('title')->label('Title')->content(fn ($record) => $record->title),
                                Placeholder::make('status')
                                    ->label('Status')
                                    ->content(function ($record) {
                                        $color = match ($record->status) {
                                            'PUBLISHED' => 'text-green-600 bg-green-500/10',
                                            'COMPLETED' => 'text-blue-600 bg-blue-500/10',
                                            default => 'text-gray-600 bg-gray-500/10',
                                        };
                                        return new HtmlString("<span class='px-3 py-1 rounded-full text-sm font-medium {$color}'>{$record->status}</span>");
                                    }),
                                Placeholder::make('event_datetime')->label('Date & Time')->content(fn ($record) => $record->event_datetime->format('l, j F Y - g:i A')),
                                Placeholder::make('location_name')->label('Location')->content(fn ($record) => $record->location_name),
                            ])->columnSpan(2),

                        Section::make('Analytics')
                            ->schema([
                                Placeholder::make('guests_count')
                                    ->label('Guest List')
                                    ->content(fn ($record) => new HtmlString("<span class='text-2xl font-bold text-primary-600'>" . $record->guests()->count() . ' / ' . $record->max_guests . "</span>")),
                                Placeholder::make('public_visits')
                                    ->label('Public Link Visits')
                                    ->content(fn ($record) => new HtmlString("<span class='text-2xl font-bold'>" . $record->public_visits . "</span>")),
                                Placeholder::make('confirmed_rsvps')
                                    ->label('Confirmed RSVPs')
                                    ->content(fn ($record) => new HtmlString("<span class='text-2xl font-bold text-success-600'>" . $record->invitations()->where('status', 'CONFIRMED')->count() . "</span>")),
                            ])->columnSpan(1),

                        Section::make('Public Invitation')
                            ->schema([
                                Placeholder::make('slug')
                                    ->label('Public Invite Link')
                                    ->content(fn ($record) => new HtmlString("<a href='" . url('/e/' . $record->slug) . "' target='_blank' class='text-primary-600 hover:underline'>" . url('/e/' . $record->slug) . "</a>")),
                            ])->columnSpan(3),
                    ])
            ]);
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('preview')
                ->label('Preview Template')
                ->icon('heroicon-o-eye')
                ->color('gray')
                ->url(fn ($record) => url('/e/' . $record->slug))
                ->openUrlInNewTab(),
            Actions\EditAction::make(),
        ];
    }
}

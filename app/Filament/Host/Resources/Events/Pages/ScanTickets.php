<?php

namespace App\Filament\Host\Resources\Events\Pages;

use App\Filament\Host\Resources\Events\EventResource;
use Filament\Resources\Pages\Page;
use App\Models\Event;
use Illuminate\Contracts\Support\Htmlable;

class ScanTickets extends Page
{
    protected static string $resource = EventResource::class;

    protected string $view = 'filament.host.resources.events.pages.scan-tickets';

    public Event $record;

    public function mount(Event $record): void
    {
        $this->record = $record;
    }

    public function getTitle(): string | Htmlable
    {
        return 'Scan Tickets: ' . $this->record->title;
    }

    protected function getHeaderActions(): array
    {
        return [];
    }
}

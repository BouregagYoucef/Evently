<?php

namespace App\Filament\Host\Resources\Events\Pages;

use App\Filament\Host\Resources\Events\EventResource;
use Filament\Resources\Pages\CreateRecord;

class CreateEvent extends CreateRecord
{
    protected static string $resource = EventResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('manage', ['record' => $this->record]);
    }
}

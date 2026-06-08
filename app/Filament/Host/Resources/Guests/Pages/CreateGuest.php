<?php

namespace App\Filament\Host\Resources\Guests\Pages;

use App\Filament\Host\Resources\Guests\GuestResource;
use Filament\Resources\Pages\CreateRecord;

class CreateGuest extends CreateRecord
{
    protected static string $resource = GuestResource::class;
}

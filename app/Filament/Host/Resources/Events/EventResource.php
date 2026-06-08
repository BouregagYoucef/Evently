<?php

namespace App\Filament\Host\Resources\Events;

use App\Filament\Host\Resources\Events\Pages\CreateEvent;
use App\Filament\Host\Resources\Events\Pages\EditEvent;
use App\Filament\Host\Resources\Events\Pages\ListEvents;
use App\Filament\Host\Resources\Events\Pages\ManageEvent;
use App\Filament\Host\Resources\Events\Pages\ScanTickets;
use App\Filament\Host\Resources\Events\Schemas\EventForm;
use App\Filament\Host\Resources\Events\Tables\EventsTable;
use App\Models\Event;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class EventResource extends Resource
{
    protected static ?string $model = Event::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        return parent::getEloquentQuery()->where('host_id', auth()->id());
    }

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Schema $schema): Schema
    {
        return EventForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return EventsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\GuestsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListEvents::route('/'),
            'create' => CreateEvent::route('/create'),
            'manage' => ManageEvent::route('/{record}'),
            'edit' => EditEvent::route('/{record}/edit'),
            'scan' => ScanTickets::route('/{record}/scan'),
        ];
    }
}

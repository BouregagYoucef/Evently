<?php

namespace App\Services;

use App\Models\Event;
use Illuminate\Support\Str;

class EventService
{
    /**
     * Create a new event for the host.
     */
    public function createEvent(array $data, int $hostId): Event
    {
        $data['host_id'] = $hostId;
        $data['slug'] = Str::slug($data['title']) . '-' . uniqid();
        $data['status'] = 'DRAFT';
        
        return Event::create($data);
    }

    /**
     * Update an existing event.
     */
    public function updateEvent(Event $event, array $data): bool
    {
        return $event->update($data);
    }

    /**
     * Delete an event.
     */
    public function deleteEvent(Event $event): bool
    {
        return $event->delete();
    }
}

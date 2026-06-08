<?php

namespace App\Services;

use App\Models\Invitation;
use App\Models\Guest;
use App\Models\Event;
use Illuminate\Support\Str;

class InvitationService
{
    /**
     * Generate an invitation for a guest.
     */
    public function createInvitation(Guest $guest, Event $event): Invitation
    {
        return Invitation::create([
            'uuid' => (string) Str::uuid(),
            'event_id' => $event->id,
            'guest_id' => $guest->id,
            'status' => 'PENDING',
        ]);
    }

    /**
     * Mark an invitation as opened.
     */
    public function markAsOpened(Invitation $invitation): void
    {
        if ($invitation->status === 'PENDING') {
            $invitation->update([
                'status' => 'OPENED',
                'opened_at' => now(),
            ]);
        }
    }

    /**
     * Process RSVP.
     */
    public function processRsvp(Invitation $invitation, bool $isAttending): void
    {
        if ($isAttending) {
            $invitation->update([
                'status' => 'CONFIRMED',
                'confirmed_at' => now(),
            ]);
            
            // Dispatch Job to Generate PDF & QR Code
            \App\Jobs\GenerateInvitationAssetsJob::dispatch($invitation);
        } else {
            $invitation->update([
                'status' => 'DECLINED',
                'declined_at' => now(),
            ]);
        }
    }
}

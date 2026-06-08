<?php

namespace App\Http\Controllers;

use App\Models\Invitation;
use App\Services\InvitationService;
use Illuminate\Http\Request;

class PrivateInviteController extends Controller
{
    /**
     * Display the private invitation page for a specific guest.
     */
    public function show(string $uuid, InvitationService $invitationService)
    {
        $invitation = Invitation::with(['event.template', 'guest'])
            ->where('uuid', $uuid)
            ->firstOrFail();

        // Mark as opened if first time
        $invitationService->markAsOpened($invitation);

        $theme = $invitation->event->template->theme_identifier;
        
        return view("themes.{$theme}.index", [
            'event' => $invitation->event,
            'guest' => $invitation->guest,
            'invitation' => $invitation,
            'isPublic' => false,
        ]);
    }
}

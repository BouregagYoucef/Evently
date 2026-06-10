<?php

namespace App\Http\Controllers;

use App\Models\Invitation;
use App\Services\InvitationService;
use App\Http\Controllers\Concerns\ResolvesMediaPaths;
use Illuminate\Http\Request;

class PrivateInviteController extends Controller
{
    use ResolvesMediaPaths;
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

        // Resolve storage paths to full URLs for theme display
        $event = $this->resolveEventMedia($invitation->event);

        $theme = $event->template->theme_identifier;
        
        return view("themes.{$theme}.index", [
            'event'      => $event,
            'guest'      => $invitation->guest,
            'invitation' => $invitation,
            'isPublic'   => false,
        ]);
    }
}

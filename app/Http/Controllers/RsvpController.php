<?php

namespace App\Http\Controllers;

use App\Models\Invitation;
use App\Http\Requests\RsvpRequest;
use App\Services\InvitationService;
use Illuminate\Http\JsonResponse;

class RsvpController extends Controller
{
    /**
     * Handle the RSVP submission for a private invitation.
     */
    public function submitPrivate(RsvpRequest $request, string $uuid, InvitationService $invitationService): JsonResponse
    {
        $invitation = Invitation::where('uuid', $uuid)->firstOrFail();
        
        $isAttending = $request->boolean('is_attending');
        $invitationService->processRsvp($invitation, $isAttending);

        return response()->json([
            'success' => true,
            'message' => $isAttending ? 'RSVP Confirmed!' : 'RSVP Declined',
            'status' => $invitation->fresh()->status,
        ]);
    }
}

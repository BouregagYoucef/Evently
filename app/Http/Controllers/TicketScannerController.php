<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Invitation;
use App\Models\Event;
use Illuminate\Support\Str;

class TicketScannerController extends Controller
{
    public function scan(Request $request)
    {
        $request->validate([
            'qr_data' => 'required|string',
            'event_id' => 'required|exists:events,id',
        ]);

        $qrData = $request->input('qr_data');
        $eventId = $request->input('event_id');

        // Verify the user owns the event
        $event = Event::findOrFail($eventId);
        if ($event->user_id !== auth()->id()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized access to this event.'], 403);
        }

        // Extract UUID from URL (e.g. http://evently.test/i/UUID or just UUID)
        // If the QR contains the full URL, we extract the last segment.
        $uuid = Str::afterLast($qrData, '/');
        
        $invitation = Invitation::where('uuid', $uuid)
            ->where('event_id', $eventId)
            ->with('guest')
            ->first();

        if (!$invitation) {
            return response()->json(['success' => false, 'message' => 'Invalid ticket or ticket does not belong to this event.'], 404);
        }

        if ($invitation->status === 'CHECKED_IN') {
            return response()->json([
                'success' => false, 
                'message' => 'This ticket has already been checked in.',
                'guest' => $invitation->guest->name
            ], 400);
        }

        // Must be CONFIRMED to check in
        if ($invitation->status !== 'CONFIRMED') {
            return response()->json([
                'success' => false, 
                'message' => 'Guest has not confirmed their attendance. Current status: ' . $invitation->status,
                'guest' => $invitation->guest->name
            ], 400);
        }

        // Check in successful
        $invitation->update(['status' => 'CHECKED_IN']);

        return response()->json([
            'success' => true,
            'message' => 'Check-in successful!',
            'guest' => [
                'name' => $invitation->guest->name,
                'type' => $invitation->guest->type,
                'companions_count' => $invitation->guest->companions_count,
            ]
        ]);
    }
}

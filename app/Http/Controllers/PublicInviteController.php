<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Guest;
use App\Services\InvitationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PublicInviteController extends Controller
{
    /**
     * Display the public event page.
     */
    public function show(string $slug)
    {
        $event = Event::with('template')
            ->where('slug', $slug)
            ->where('status', 'PUBLISHED')
            ->firstOrFail();

        // Increment visits in the background using Laravel 11 defer() to avoid slowing down the response
        defer(fn () => $event->increment('public_visits'));

        // Inject data to the theme views
        $theme = $event->template->theme_identifier;
        
        return view("themes.{$theme}.index", [
            'event' => $event,
            'isPublic' => true,
        ]);
    }

    /**
     * Handle public registration and redirect to private invite.
     */
    public function register(Request $request, string $slug, InvitationService $invitationService)
    {
        $event = Event::where('slug', $slug)
            ->where('status', 'PUBLISHED')
            ->firstOrFail();

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'companions_count' => 'nullable|integer|min:0|max:10',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        // Create the Guest
        $guest = $event->guests()->create([
            'name' => $request->name,
            'phone' => $request->phone,
            'email' => $request->email,
            'type' => 'REGULAR',
            'companions_count' => $request->companions_count ?? 0,
        ]);

        // Generate the Private Invitation
        $invitation = $invitationService->createInvitation($guest, $event);

        // Auto-confirm RSVP instantly for public link registrations
        $invitationService->processRsvp($invitation, true);

        // Return UUID instead of redirecting so the frontend can swap the UI seamlessly
        return response()->json([
            'success' => true,
            'uuid' => $invitation->uuid,
        ]);
    }
}

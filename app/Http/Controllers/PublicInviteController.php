<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Guest;
use App\Services\InvitationService;
use App\Http\Controllers\Concerns\ResolvesMediaPaths;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PublicInviteController extends Controller
{
    use ResolvesMediaPaths;
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

        // Resolve storage paths to full URLs for theme display
        $event = $this->resolveEventMedia($event);

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

        // Enforce guest quota: prevent registration when event is full
        if ($event->guests()->count() >= $event->max_guests) {
            return response()->json([
                'success' => false,
                'message' => 'عذراً، اكتمل عدد المسجلين في هذه المناسبة.'
            ], 403);
        }

        // Create the Guest — Guest::booted() automatically creates the invitation (PENDING)
        $guest = $event->guests()->create([
            'name' => $request->name,
            'phone' => $request->phone,
            'email' => $request->email,
            'type' => 'REGULAR',
            'companions_count' => $request->companions_count ?? 0,
        ]);

        // Reuse the auto-created invitation instead of creating a duplicate
        $invitation = $guest->invitations()->first();

        // Auto-confirm RSVP instantly for public link registrations
        $invitationService->processRsvp($invitation, true);

        // Return UUID instead of redirecting so the frontend can swap the UI seamlessly
        return response()->json([
            'success' => true,
            'uuid' => $invitation->uuid,
        ]);
    }
}

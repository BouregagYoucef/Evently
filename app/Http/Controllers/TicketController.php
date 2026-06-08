<?php

namespace App\Http\Controllers;

use App\Models\Invitation;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    /**
     * Download the PDF ticket for a confirmed invitation.
     */
    public function download(string $uuid)
    {
        $invitation = Invitation::where('uuid', $uuid)
            ->where('status', 'CONFIRMED')
            ->firstOrFail();

        if (! $invitation->pdf_path) {
            // Generate ticket on-the-fly synchronously
            app(\App\Jobs\GenerateInvitationAssetsJob::class, ['invitation' => $invitation])->handle();
            
            // Refresh to get the generated path
            $invitation->refresh();
        }

        if (! $invitation->pdf_path) {
            abort(404, 'Ticket could not be generated. Please contact support.');
        }

        return response()->download(storage_path('app/public/' . $invitation->pdf_path));
    }
}

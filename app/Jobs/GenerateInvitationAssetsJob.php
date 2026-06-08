<?php

namespace App\Jobs;

use App\Models\Invitation;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class GenerateInvitationAssetsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $invitation;

    /**
     * Create a new job instance.
     */
    public function __construct(Invitation $invitation)
    {
        $this->invitation = $invitation;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // Refresh the invitation to ensure we have the latest relations (event, guest)
        $this->invitation->loadMissing(['event.host', 'guest']);

        $event = $this->invitation->event;
        $guest = $this->invitation->guest;

        // 1. Generate QR Code
        // The QR code links to the private invitation URL with the unique UUID
        $url = url('/i/' . $this->invitation->uuid);
        $qrCodeImage = QrCode::format('svg')->size(400)->margin(1)->generate($url);
        
        $qrCodeFilename = 'invitations/qr_' . $this->invitation->uuid . '.svg';
        Storage::disk('public')->put($qrCodeFilename, $qrCodeImage);

        // 2. Generate PDF Ticket
        // Convert the QR code to base64 so we can easily embed it in the PDF
        $qrCodeBase64 = base64_encode($qrCodeImage);

        // Prepare Arabic text shaper
        $arabic = new \ArPHP\I18N\Arabic('Glyphs');

        // Prepare Background Image
        $bgPath = public_path('images/themes/user_wedding_blank_banner.jpg');
        $bgBase64 = '';
        if (file_exists($bgPath)) {
            $bgBase64 = base64_encode(file_get_contents($bgPath));
        }

        $pdf = Pdf::loadView('pdf.ticket', [
            'invitation' => $this->invitation,
            'event' => $event,
            'guest' => $guest,
            'qrCodeBase64' => $qrCodeBase64,
            'arabic' => $arabic,
            'bgBase64' => $bgBase64,
        ])->setPaper('a4', 'landscape')->setOptions(['dpi' => 150, 'defaultFont' => 'sans-serif', 'isRemoteEnabled' => true]);

        $pdfFilename = 'invitations/ticket_' . $this->invitation->uuid . '.pdf';
        Storage::disk('public')->put($pdfFilename, $pdf->output());

        // 3. Update the invitation record with the paths
        $this->invitation->update([
            'qr_code_path' => $qrCodeFilename,
            'pdf_path' => $pdfFilename,
        ]);
        
        // 4. Optionally dispatch an email with the PDF attached
        // Mail::to($guest->email)->send(new \App\Mail\TicketMail($this->invitation));
    }
}

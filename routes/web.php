<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PublicInviteController;
use App\Http\Controllers\PrivateInviteController;
use App\Http\Controllers\RsvpController;
use App\Http\Controllers\TicketController;

Route::get('/', function () {
    $templates = \App\Models\Template::where('is_active', true)->get();
    return view('landing', compact('templates'));
});

Route::get('/e/{slug}', [PublicInviteController::class, 'show'])->name('public.invite.show');
Route::post('/e/{slug}/register', [PublicInviteController::class, 'register'])
    ->name('public.invite.register')
    ->middleware('throttle:5,1'); // Limit to 5 registrations per minute

Route::get('/i/{uuid}', [PrivateInviteController::class, 'show'])->name('private.invite.show');
Route::post('/i/{uuid}/rsvp', [RsvpController::class, 'submitPrivate'])
    ->name('private.rsvp.submit')
    ->middleware('throttle:10,1'); // Limit to 10 RSVPs per minute

Route::get('/i/{uuid}/ticket', [TicketController::class, 'download'])->name('private.ticket.download');

// Host Scanner API Route
Route::middleware('auth')->post('/host/scan-ticket', [\App\Http\Controllers\TicketScannerController::class, 'scan'])->name('host.scan-ticket');

// Public Live Template Preview (rate-limited to prevent scraping)
Route::middleware('throttle:30,1')->get('/templates/{template}/preview', [\App\Http\Controllers\TemplatePreviewController::class, 'preview'])->name('template.preview');

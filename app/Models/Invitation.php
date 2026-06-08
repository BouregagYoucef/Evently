<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Invitation extends Model
{
    protected $fillable = ['uuid', 'event_id', 'guest_id', 'status', 'qr_code_path', 'pdf_path', 'opened_at', 'confirmed_at', 'declined_at'];
    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'opened_at' => 'datetime',
            'confirmed_at' => 'datetime',
            'declined_at' => 'datetime',
        ];
    }

    /**
     * Get the event that owns the invitation.
     */
    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    /**
     * Get the guest that owns the invitation.
     */
    public function guest(): BelongsTo
    {
        return $this->belongsTo(Guest::class);
    }

    protected static function booted()
    {
        static::deleting(function ($invitation) {
            if ($invitation->pdf_path && \Illuminate\Support\Facades\Storage::disk('public')->exists($invitation->pdf_path)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($invitation->pdf_path);
            }
            if ($invitation->qr_code_path && \Illuminate\Support\Facades\Storage::disk('public')->exists($invitation->qr_code_path)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($invitation->qr_code_path);
            }
        });
    }
}

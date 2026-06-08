<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Guest extends Model
{
    protected $fillable = ['event_id', 'name', 'phone', 'email', 'type', 'companions_count'];
    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'companions_count' => 'integer',
        ];
    }

    /**
     * Get the event that owns the guest.
     */
    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    /**
     * Get the invitations for the guest.
     */
    public function invitations(): HasMany
    {
        return $this->hasMany(Invitation::class);
    }

    /**
     * Get the primary invitation (useful when guest is scoped by event).
     */
    public function invitation()
    {
        return $this->hasOne(Invitation::class)->latestOfMany();
    }

    protected static function booted()
    {
        static::created(function ($guest) {
            // Automatically generate an invitation for the guest
            $guest->invitations()->create([
                'uuid' => \Illuminate\Support\Str::uuid()->toString(),
                'event_id' => $guest->event_id,
                'status' => 'PENDING',
            ]);
        });

        static::deleting(function ($guest) {
            $guest->invitations->each->delete();
        });
    }
}

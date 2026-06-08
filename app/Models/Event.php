<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Event extends Model
{
    protected $fillable = ['host_id', 'template_id', 'slug', 'title', 'event_datetime', 'location_name', 'content_data', 'max_guests', 'status'];
    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'event_datetime' => 'datetime',
            'content_data' => 'array',
        ];
    }

    /**
     * Get the host that owns the event.
     */
    public function host(): BelongsTo
    {
        return $this->belongsTo(User::class, 'host_id');
    }

    /**
     * Get the template used by the event.
     */
    public function template(): BelongsTo
    {
        return $this->belongsTo(Template::class);
    }

    protected static function booted()
    {
        static::created(function ($event) {
            \App\Models\AuditLog::create([
                'user_id' => $event->host_id,
                'event_id' => $event->id,
                'action' => 'CREATED_EVENT',
                'description' => "Created new event: {$event->title}",
                'ip_address' => request()->ip(),
            ]);
        });

        static::deleting(function ($event) {
            // Delete guests to trigger their physical files cleanup
            $event->guests->each->delete();
            
            // Delete gallery and image files from content_data if any exist
            if (is_array($event->content_data)) {
                foreach ($event->content_data as $key => $value) {
                    if (is_string($value) && \Illuminate\Support\Facades\Storage::disk('public')->exists($value)) {
                        \Illuminate\Support\Facades\Storage::disk('public')->delete($value);
                    } elseif (is_array($value)) {
                        foreach ($value as $path) {
                            if (is_string($path) && \Illuminate\Support\Facades\Storage::disk('public')->exists($path)) {
                                \Illuminate\Support\Facades\Storage::disk('public')->delete($path);
                            }
                        }
                    }
                }
            }
        });

        static::deleted(function ($event) {
            \App\Models\AuditLog::create([
                'user_id' => $event->host_id,
                'event_id' => null,
                'action' => 'DELETED_EVENT',
                'description' => "Deleted event: {$event->title}",
                'ip_address' => request()->ip(),
            ]);
        });
    }

    /**
     * Get the guests for the event.
     */
    public function guests(): HasMany
    {
        return $this->hasMany(Guest::class);
    }

    /**
     * Get the invitations for the event.
     */
    public function invitations(): HasMany
    {
        return $this->hasMany(Invitation::class);
    }
}

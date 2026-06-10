<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

use Illuminate\Database\Eloquent\Relations\HasMany;

use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;

class User extends Authenticatable implements FilamentUser
{
    protected $fillable = [
        'name', 'email', 'password', 'email_verified_at', 'role', 'is_approved', 'max_events', 'max_guests_per_event'
    ];
    
    protected $hidden = [
        'password', 'remember_token'
    ];
    protected static function booted(): void
    {
        static::creating(function (User $user) {
            if (empty($user->role)) {
                $user->role = 'host';
            }
            if (! isset($user->is_approved)) {
                $user->is_approved = true; // Auto-approve for now so user can test seamlessly
            }
        });

        static::deleting(function (User $user) {
            $user->events->each->delete();
        });
    }
    /**
     * Determine if the user can access the Filament panel.
     */
    public function canAccessPanel(Panel $panel): bool
    {
        if ($panel->getId() === 'admin') {
            return $this->role === 'superadmin';
        }
        
        if ($panel->getId() === 'host') {
            return $this->role === 'host';
        }

        return false;
    }
    /**
     * Get the events for the host.
     */
    public function events(): HasMany
    {
        return $this->hasMany(Event::class, 'host_id');
    }
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_approved' => 'boolean',
        ];
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'subscription_id',
        'subscription_expires_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at'      => 'datetime',
            'subscription_expires_at' => 'datetime',
            'password'               => 'hashed',
        ];
    }

    // ── Relationships ──────────────────────────────────────────────
    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    public function subscription()
    {
        return $this->belongsTo(Subscription::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    // ── Helper Methods ─────────────────────────────────────────────
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isPro(): bool
    {
        return $this->subscription
            && $this->subscription->name === 'Pro'
            && $this->subscription_expires_at
            && $this->subscription_expires_at->isFuture();
    }

    public function hasReachedTaskLimit(): bool
    {
        // Free plan: max 10 tasks. Pro plan: unlimited.
        if ($this->isPro()) return false;
        return $this->tasks()->count() >= 10;
    }
}
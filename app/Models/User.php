<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * Mass assignable attributes.
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'subscription_id',
        'subscription_expires_at',
    ];

    /**
     * Hidden attributes.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Attribute casting.
     */
    protected function casts(): array
    {
        return [
            'email_verified_at'       => 'datetime',
            'subscription_expires_at' => 'datetime',
            'password'                => 'hashed',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

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

    public function review()
    {
        return $this->hasOne(Review::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Helper Methods
    |--------------------------------------------------------------------------
    */

    /**
     * Check if user is an admin.
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Check if user has an active Pro subscription.
     */
    public function isPro(): bool
    {
        return $this->subscription
            && $this->subscription->name === 'Pro'
            && $this->subscription_expires_at
            && $this->subscription_expires_at->isFuture();
    }

    /**
     * Total number of tasks created by user.
     */
    public function taskCount(): int
    {
        return $this->tasks()->count();
    }

    /**
     * Free users can create only 10 tasks.
     * Pro users have unlimited tasks.
     */
    public function hasReachedTaskLimit(): bool
    {
        return ! $this->isPro() && $this->taskCount() >= 10;
    }

    /**
     * Check whether user can create a new task.
     */
    public function canCreateTask(): bool
    {
        return ! $this->hasReachedTaskLimit();
    }
}
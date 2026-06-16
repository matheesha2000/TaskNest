<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'status',
        'priority',
        'category',
        'due_date',
    ];

    protected $casts = [
        'due_date' => 'date',
    ];

    // ── Relationships ──────────────────────────────────────────────
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // ── Scopes ─────────────────────────────────────────────────────
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeInProgress($query)
    {
        return $query->where('status', 'in_progress');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeOverdue($query)
    {
        return $query->where('due_date', '<', now())
                     ->where('status', '!=', 'completed');
    }

    // ── Helpers ────────────────────────────────────────────────────
    public function isOverdue(): bool
    {
        return $this->due_date && $this->due_date->isPast() && $this->status !== 'completed';
    }

    public function statusBadgeClass(): string
    {
        return match($this->status) {
            'pending'     => 'bg-yellow-100 text-yellow-800',
            'in_progress' => 'bg-blue-100 text-blue-800',
            'completed'   => 'bg-green-100 text-green-800',
            default       => 'bg-gray-100 text-gray-800',
        };
    }

    public function priorityBadgeClass(): string
    {
        return match($this->priority) {
            'high'   => 'bg-red-100 text-red-800',
            'medium' => 'bg-orange-100 text-orange-800',
            'low'    => 'bg-gray-100 text-gray-600',
            default  => 'bg-gray-100 text-gray-600',
        };
    }
}
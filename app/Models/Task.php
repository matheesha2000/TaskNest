<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;

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
        'due_date' => 'datetime',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Query Scopes
    |--------------------------------------------------------------------------
    */

    public function scopePending(Builder $query): Builder
    {
        return $query->where('status', 'pending');
    }

    public function scopeInProgress(Builder $query): Builder
    {
        return $query->where('status', 'in_progress');
    }

    public function scopeCompleted(Builder $query): Builder
    {
        return $query->where('status', 'completed');
    }

    public function scopeOverdue(Builder $query): Builder
    {
        return $query
            ->whereNotNull('due_date')
            ->where('due_date', '<', now())
            ->where('status', '!=', 'completed');
    }

    /*
    |--------------------------------------------------------------------------
    | Helpers
    |--------------------------------------------------------------------------
    */

    public function isOverdue(): bool
    {
        if (!$this->due_date) {
            return false;
        }

        return $this->status !== 'completed'
            && Carbon::parse($this->due_date)->isPast();
    }

    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isInProgress(): bool
    {
        return $this->status === 'in_progress';
    }

    /*
    |--------------------------------------------------------------------------
    | UI Helpers
    |--------------------------------------------------------------------------
    */

    public function statusBadgeClass(): string
    {
        return match ($this->status) {
            'pending'     => 'bg-amber-50 text-amber-700 border border-amber-200/60',
            'in_progress' => 'bg-blue-50 text-blue-700 border border-blue-200/60',
            'completed'   => 'bg-emerald-50 text-emerald-700 border border-emerald-200/60',
            default       => 'bg-slate-50 text-slate-600 border border-slate-200/60',
        };
    }

    public function priorityBadgeClass(): string
    {
        return match ($this->priority) {
            'high'   => 'bg-rose-50 text-rose-700 border border-rose-200/60',
            'medium' => 'bg-orange-50 text-orange-700 border border-orange-200/60',
            'low'    => 'bg-slate-50 text-slate-600 border border-slate-200/60',
            default  => 'bg-slate-50 text-slate-600 border border-slate-200/60',
        };
    }
}
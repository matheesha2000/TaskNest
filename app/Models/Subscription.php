<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'price', 'duration', 'features'];

    protected $casts = [
        'features' => 'array',
        'price'    => 'decimal:2',
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function isFree(): bool
    {
        return $this->price == 0;
    }
}
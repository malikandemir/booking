<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CarBooking extends Model
{
    protected $fillable = [
        'user_id',
        'start_time',
        'end_time',
        'purpose',
        'status',
        'rejection_reason',
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function hasConflict(CarBooking $booking): bool
    {
        return $this->start_time < $booking->end_time && $this->end_time > $booking->start_time;
    }
}

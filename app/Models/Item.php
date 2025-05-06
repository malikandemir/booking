<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'icon',
        'type',
        'description',
        'status',
        'company_id',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function userRoles(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'resource_user_roles')
            ->withPivot('role_id')
            ->withTimestamps();
    }

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'resource_user_roles')
            ->withPivot('user_id')
            ->withTimestamps();
    }
}

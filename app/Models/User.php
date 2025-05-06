<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\DB;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    // Role constants
    const ROLE_USER = 0;
    const ROLE_ADMIN = 1;
    const ROLE_SUPER_ADMIN = 2;

    protected $fillable = [
        'name',
        'email',
        'password',
        'is_admin',
        'company_id'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_admin' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function bookings()
    {
        return $this->hasMany(CarBooking::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function isSuperAdmin()
    {
        return $this->is_admin === self::ROLE_SUPER_ADMIN;
    }

    public function isAdmin()
    {
        return $this->is_admin === self::ROLE_ADMIN;
    }

    public function isUser()
    {
        return $this->is_admin === self::ROLE_USER;
    }

    public function scopeInCompany($query, $companyId)
    {
        return $query->where('company_id', $companyId);
    }

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'resource_user_roles')
            ->withPivot('item_id')
            ->withTimestamps();
    }

    /**
     * Get the roles for items that this user can manage
     */
    public function itemRoles()
    {
        return $this->belongsToMany(Role::class, 'user_item_roles')
            ->withPivot('item_id')
            ->withTimestamps();
    }

    public function canBook(Item $item): bool
    {
        return $this->itemRoles()
            ->wherePivot('item_id', $item->id)
            ->wherePivot('role_id', Role::where('slug', 'booker')->first()->id)
            ->exists();
    }

    public function canApprove(Item $item)
    {
        $approverRole = Role::where('name', 'Booking Approver')->first();
        if (!$approverRole) {
            return false;
        }

        return DB::table('user_item_roles')
            ->where('user_id', $this->id)
            ->where('item_id', $item->id)
            ->where('role_id', $approverRole->id)
            ->exists();
    }

    public function canApproveBooking(Booking $booking)
    {
        // Users cannot approve their own bookings
        if ($this->id === $booking->user_id) {
            return false;
        }

        return $this->canApprove($booking->item);
    }

    public function getPendingBookingsToApprove()
    {
        $query = Booking::where('status', 'pending')
            ->where('user_id', '!=', $this->id); // Exclude user's own bookings

        if ($this->isAdmin()) {
            return $query->get();
        }

        $approverRole = Role::where('name', 'Booking Approver')->first();
        if (!$approverRole) {
            return collect();
        }

        $itemIds = DB::table('user_item_roles')
            ->where('user_id', $this->id)
            ->where('role_id', $approverRole->id)
            ->pluck('item_id');

        return $query->whereIn('item_id', $itemIds)->get();
    }

    protected static function boot()
    {
        parent::boot();

        // When creating a user
        static::creating(function ($user) {
            // If user is not super admin, company_id is required
            if ($user->is_admin !== self::ROLE_SUPER_ADMIN && !$user->company_id) {
                throw new \Exception('Company ID is required for admins and users.');
            }
        });

        // When updating a user
        static::updating(function ($user) {
            // If changing role to admin or user, company_id is required
            if ($user->is_admin !== self::ROLE_SUPER_ADMIN && !$user->company_id) {
                throw new \Exception('Company ID is required for admins and users.');
            }
            // If changing to super admin, remove company_id
            if ($user->is_admin === self::ROLE_SUPER_ADMIN) {
                $user->company_id = null;
            }
        });
    }
}

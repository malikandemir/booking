<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Role extends Model
{
    protected $fillable = ['name', 'slug'];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'resource_user_roles')
            ->withPivot('item_id')
            ->withTimestamps();
    }

    public function items(): BelongsToMany
    {
        return $this->belongsToMany(Item::class, 'resource_user_roles')
            ->withPivot('user_id')
            ->withTimestamps();
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'status'
    ];

    public function items()
    {
        return $this->hasMany(Item::class);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }
}

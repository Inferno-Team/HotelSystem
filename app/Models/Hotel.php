<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Hotel extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'location',
        'manager_id'
    ];

    public function manager(): BelongsTo
    {
        return $this->belongsTo(User::class, 'manager_id');
    }
    public function rooms(): HasMany
    {
        return $this->hasMany(Room::class, 'hotel_id');
    }
    public function employees(): HasMany
    {
        return $this->hasMany(HotelEmployee::class, 'hotel_id')->with('employee');
    }
}

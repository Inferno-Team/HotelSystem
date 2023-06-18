<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MeetingRoom extends Model
{
    use HasFactory;
    protected $appends = ['is_occupied'];

    protected $fillable = [
        'hotel_id', 'price', 'type'
    ];

    public function hotel(): BelongsTo
    {
        return $this->belongsTo(Hotel::class, 'hotel_id');
    }
    public function isOccupied(): Attribute
    {
        return new Attribute(
            get: function ($value) {
                $customer = $this->room_customer->where('valid', true);
                info($customer);
                return isset($customer) && count($this->room_customer) > 0;
            }
        );
    }
    public function room_customer(): HasMany
    {
        return $this->hasMany(MeetingRoomCustomer::class, 'room_id');
    }
}

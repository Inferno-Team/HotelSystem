<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RoomOccupationRequest extends Model
{
    use HasFactory;
    protected $append = ['is_request'];

    protected $fillable = [
        'customer_id',
        // 'room_id',
        'room_type',
        'from',
        'to',
        'price',
        'status'
    ];
    public function isRequest(): Attribute
    {
        return new Attribute(
            get: fn () => $this->status == 'requested',
        );
    }
    public function customer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'customer_id');
    }
}

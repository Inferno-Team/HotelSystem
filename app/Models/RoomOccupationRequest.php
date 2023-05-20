<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RoomOccupationRequest extends Model
{
    use HasFactory;
    protected $fillable = [
        'customer_id',
        // 'room_id',
        'room_type',
        'from',
        'to',
        'price'
    ];
    public function customer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'customer_id');
    }
}

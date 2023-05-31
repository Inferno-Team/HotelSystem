<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CustomerFav extends Model
{
    use HasFactory;
    protected $fillable = [
        'customer_id',
        'room_id'
    ];
    public function customer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'customer_id');
    }
    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class, 'room_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GarageOccupationRequest extends Model
{
    use HasFactory;
    protected $append = ['is_request'];
    protected $fillable = [
        'customer_id',
        // 'parking_space_id',
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
    public function parking_space(): BelongsTo
    {
        return $this->belongsTo(ParkingSpace::class, 'parking_space_id');
    }
}

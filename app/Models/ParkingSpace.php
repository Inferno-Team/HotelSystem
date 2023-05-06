<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ParkingSpace extends Model
{
    use HasFactory;
    protected $fillable = [
        'garage_id',
        'number'
    ];

    public function garage(): BelongsTo
    {
        return $this->belongsTo(Garage::class, 'garage_id');
    }
}

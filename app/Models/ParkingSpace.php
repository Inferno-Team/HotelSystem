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
        'number',
        'price'
    ];

    public function garage(): BelongsTo
    {
        return $this->belongsTo(Garage::class, 'garage_id');
    }
    public static function boot()
    {
        parent::boot();

        self::creating(function ($model) {
            $garage = Garage::where('id', $model->garage_id)->first();
            $model->price = $garage->price;
        });
    }
}

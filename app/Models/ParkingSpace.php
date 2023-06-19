<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ParkingSpace extends Model
{
    use HasFactory;
    protected $appends = ['is_occupied'];

    protected $fillable = [
        'garage_id',
        'number',
        'price'
    ];
    public function parking_customer(): HasMany
    {
        return $this->hasMany(CustomerParking::class, 'parking_id');
    }
    public function isOccupied(): Attribute
    {
        return new Attribute(
            get: function ($value) {
                $customer = $this->parking_customer->where('valid', true)->first();
                return $customer !== null;
            }
        );
    }
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

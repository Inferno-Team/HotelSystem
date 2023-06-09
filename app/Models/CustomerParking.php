<?php

namespace App\Models;

use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CustomerParking extends Model
{
    use HasFactory;
    protected $appends = ['valid'];

    protected $fillable = [
        'customer_id',
        'parking_id',
        'from',
        'to'
    ];
    public function valid(): Attribute
    {
        return new Attribute(
            get: function ($value) {
                $startDate = Carbon::createFromDate($this->from);
                $endDate = Carbon::createFromDate($this->to);
                return Carbon::now()->between($startDate, $endDate);
            }
        );
    }
    public function parking_space(): BelongsTo
    {
        return $this->belongsTo(ParkingSpace::class, 'parking_id');
    }
}

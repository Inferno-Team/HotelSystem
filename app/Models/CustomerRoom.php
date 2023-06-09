<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class CustomerRoom extends Model
{
    use HasFactory;
    protected $appends = ['valid'];
    protected $fillable = [
        'room_id',
        'customer_id',
        'from',
        'to',
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
    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class, 'room_id');
    }
    public function customer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'customer_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HotelEmployee extends Model
{
    use HasFactory;
    protected $fillable = [
        'employee_id',
        'hotel_id',
    ];
    public function employee(): BelongsTo
    {
        return  $this->belongsTo(User::class, 'employee_id');
    }
    public function hotel(): BelongsTo
    {
        return $this->belongsTo(User::class, 'hotel_id');
    }
}

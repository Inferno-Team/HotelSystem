<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MeetingRoomOccupationRequest extends Model
{
    use HasFactory;
    protected $append = ['is_request'];

    protected $fillable = [
        "customer_id",
        "from",
        "from",
        "to",
        "price",
        "type",
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

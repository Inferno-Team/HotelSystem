<?php

namespace App\Models;

use App\Http\Helpers\FolderHelper;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Room extends Model
{
    use HasFactory;
    protected $appends = ['is_occupied', 'images'];
    protected $fillable = [
        'number',
        'hotel_id',
        'type',
        'price'
    ];
    public function isOccupied(): Attribute
    {
        return new Attribute(
            get: function ($value) {
                $customer = $this->room_customer->where('valid', true)->first();
                return isset($customer) && count($this->room_customer) > 0;
            }
        );
    }
    public function hotel(): BelongsTo
    {
        return $this->belongsTo(Hotel::class, 'hotel_id');
    }
    public function images_relation(): HasMany
    {
        return $this->hasMany(RoomImage::class, 'room_id');
    }
    public function room_customer(): HasMany
    {
        return $this->hasMany(CustomerRoom::class, 'room_id');
    }

    public function images(): Attribute
    {
        return new Attribute(
            get: function ($value) {
                $imgs = $this->images_relation;
                return FolderHelper::getRoomImages($this->hotel->name, $this->number, $imgs);
            }
        );
    }
    /* public static function boot()
    {
        parent::boot();

        self::creating(function ($model) {
            $model->is_occupied=false;
        });
    } */
}

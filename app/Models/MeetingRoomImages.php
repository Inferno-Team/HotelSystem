<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MeetingRoomImages extends Model
{
    use HasFactory;
    protected $fillable = [
        'file_name',
        'room_id'
    ];
}

<?php

namespace App\Http\Controllers\customer;

use App\Models\Hotel;
use Illuminate\Http\Request;
use App\Http\Traits\LocalResponse;
use App\Http\Controllers\Controller;

class HotelController extends Controller
{
    public function getAll()
    {
        $hotels = Hotel::with('manager', 'rooms')->get();
        return LocalResponse::returnData('hotels', $hotels);
    }
    public function getHotel(int $id)
    {
        $hotel = Hotel::where('id', $id)->with('manager', 'rooms')->first();
        return LocalResponse::returnData('hotel', $hotel);
    }
}

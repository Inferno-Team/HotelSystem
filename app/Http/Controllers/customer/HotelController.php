<?php

namespace App\Http\Controllers\customer;

use App\Models\Hotel;
use Illuminate\Http\Request;
use App\Http\Traits\LocalResponse;
use App\Http\Controllers\Controller;
use App\Models\CustomerFav;
use App\Models\CustomerParking;
use App\Models\CustomerRoom;
use App\Models\Garage;
use App\Models\MeetingRoomCustomer;
use App\Models\ParkingSpace;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

use function PHPUnit\Framework\returnSelf;

class HotelController extends Controller
{
    public function getAll()
    {
        $hotels = Hotel::with('manager', 'rooms')->get();
        return LocalResponse::returnData('hotels', $hotels);
    }
    public function getHotel(int $id)
    {
        $customer = Auth::user();
        $hotel = Hotel::where('id', $id)->with('manager', 'rooms.fav_customer')->get()
            ->map(function ($hotel) use ($customer) {
                return (object)[
                    'name' => $hotel->name,
                    'location' => $hotel->location,
                    "rooms" => $hotel->rooms->map(function ($room) use ($customer) {
                        return (object)[
                            "id" => $room->id,
                            "number" => $room->number,
                            "type" => $room->type,
                            "price" => $room->price,
                            "is_occupied" => $room->is_occupied,
                            "images" => $room->images,
                            "is_fav" => $room->fav_customer->where('customer_id', $customer->id)->first() !== null
                        ];
                    }),
                ];
            })->first();
        return LocalResponse::returnData('hotel', $hotel);
    }
    public function checkGarage()
    {
        $customer = Auth::user();
        // check if this customer has a valid [CustomerParking record]
        $customerParking = CustomerParking::where('customer_id', $customer->id)->get();
        if (count($customerParking) == 0) {
            // this user never request parking before
            return LocalResponse::returnData(
                'result',
                ['value' => false, 'parking' => (object)[]],
                'you have no parking spot.'
            );
        }
        // check if this customer has valid parking :
        foreach ($customerParking as $park) {
            if ($park->valid) {
                return LocalResponse::returnData(
                    'result',
                    ['value' => true, 'parking' => $park->parking_space->number],
                    'you have parking spot.'
                );
            }
        }
        // there is no valid parking for this customer
        return LocalResponse::returnData(
            'result',
            ['value' => false, 'parking' => (object)[]],
            'you have no parking spot.'
        );
    }
    public function getMyReservations()
    {
        $customer = Auth::user();
        // get all this user reservations [ room , parking-space , conference-room ]
        $customerParking  = CustomerParking::where('customer_id', $customer->id)->get()->filter(function ($park) {
            return $park->valid;
        })->values()->first();
        $customerRooms = CustomerRoom::where('customer_id', $customer->id)->with('room')->get()->filter(function ($item) {
            return $item->valid;
        })->values();
        $meetingRoomCustomer = MeetingRoomCustomer::where('customer_id', $customer->id)->get()->filter(function ($item) {
            return $item->valid;
        })->values();

        return LocalResponse::returnData('reservations', [
            'garage' => $customerParking,
            'room' => $customerRooms,
            'meeting_room' => $meetingRoomCustomer,

        ], 'your reservations');
    }
    public function getHotelParkingSpace(int $id)
    {
        // check if this id is belongs to hotel and if exists
        $hotel = Hotel::where('id', $id)->first();
        if (!isset($hotel)) return LocalResponse::returnError('where is hotel with this id', 404);
        $hotelGarage = Garage::where('hotel_id', $hotel->id)->first();
        $parkingSpaces = ParkingSpace::where('garage_id', $hotelGarage->id)->get();
        return LocalResponse::returnData('spaces', $parkingSpaces);
    }
}

<?php

namespace App\Http\Controllers\customer;

use App\Models\Room;
use Illuminate\Http\Request;
use App\Http\Traits\LocalResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\customer\OccupationGarageRequest;
use App\Models\RoomOccupationRequest;
use App\Http\Requests\customer\OccupationRequest;
use App\Models\GarageOccupationRequest;
use App\Models\ParkingSpace;
use App\Models\User;
use App\Notifications\NewOccupationRequest;
use App\Notifications\NewParkingSpaceOccupationRequest;

class RequestController extends Controller
{
    public function requestOccupationRoom(OccupationRequest $request)
    {
        $room = Room::where('id', $request->room_id)->first();
        $oRequerst = RoomOccupationRequest::create($request->values());
        $oRequerst->price = $room->price;
        $oRequerst->save();
        $manager = $room->hotel->manager;
        $employess = $room->hotel->employees;

        $manager->notify(new NewOccupationRequest($oRequerst));
        foreach ($employess as $emp) {
            $emp->employee->notify(new NewOccupationRequest($oRequerst));
        }
        return LocalResponse::returnData("request", $oRequerst, 'request saved.');
    }
    public function requestOccupationGarage(OccupationGarageRequest $request)
    {
        $parkingSpace = ParkingSpace::where('id', $request->space_id)->first();
        $oRequerst = GarageOccupationRequest::create($request->values());
        $oRequerst->price = $parkingSpace->price;
        $oRequerst->save();

        $manager = $parkingSpace->garage->hotel->manager;
        $employess = $parkingSpace->garage->hotel->employees;
        $manager->notify(new NewParkingSpaceOccupationRequest($oRequerst));
        foreach ($employess as $emp) {
            $emp->employee->notify(new NewParkingSpaceOccupationRequest($oRequerst));
        }
        return LocalResponse::returnData("request", $oRequerst, 'request saved.');
    }
}

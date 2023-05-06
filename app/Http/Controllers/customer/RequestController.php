<?php

namespace App\Http\Controllers\customer;

use App\Models\Room;
use Illuminate\Http\Request;
use App\Http\Traits\LocalResponse;
use App\Http\Controllers\Controller;
use App\Models\RoomOccupationRequest;
use App\Http\Requests\customer\OccupationRequest;
use App\Models\User;
use App\Notifications\NewOccupationRequest;

class RequestController extends Controller
{
    public function requestOccupation(OccupationRequest $request)
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
}

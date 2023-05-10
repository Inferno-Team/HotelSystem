<?php

namespace App\Http\Controllers\employee;

use App\Models\CustomerRoom;
use Illuminate\Http\Request;
use App\Http\Traits\LocalResponse;
use App\Http\Controllers\Controller;
use App\Models\RoomOccupationRequest;
use App\Notifications\ResponseToOccupationNotification;
use App\Http\Requests\employee\ResponseToOccupationRequest;
use App\Http\Requests\employee\ResponseToParkingOccupation;
use App\Models\CustomerParking;
use App\Models\GarageOccupationRequest;
use App\Models\HotelEmployee;
use App\Models\ParkingSpace;
use App\Models\Room;
use Illuminate\Support\Facades\Auth;

class OccupationController extends Controller
{
    public function responseToRequest(ResponseToOccupationRequest $request)
    {
        $oRequest = RoomOccupationRequest::where('id', $request->request_id)->first();
        if ($oRequest->status != 'requested') {
            // this request has been already handled
            return LocalResponse::returnMessage("This request had been already handled");
        }
        $oRequest->status = $request->status;
        $room = Room::where('id', $oRequest->room_id)->first();
        // check if this employee belongs to this room's hotel
        $hotel = $room->hotel;
        $employee = HotelEmployee::where('employee_id', Auth::user()->id)->first();
        if ($employee->hotel_id != $hotel->id) {
            return LocalResponse::returnMessage("you don't belongs to this hotel .", 403);
        }
        $customer = $oRequest->customer;
        if ($room->is_occupied) {
            // you can't approve this request because room is occupied
            // if you send stauts equal approved we will rejected .
            $oRequest->status = 'rejected';
            $oRequest->save();
            $customer->notify(new ResponseToOccupationNotification($oRequest->status, $oRequest->id));
            return LocalResponse::returnMessage("this room is occupied", 406);
        } else {
            $oRequest->save();
            $customer->notify(new ResponseToOccupationNotification($oRequest->status, $oRequest->id));
            if ($oRequest->status == 'approved') {
                CustomerRoom::create([
                    'room_id' => $oRequest->room_id,
                    'customer_id' => $oRequest->customer_id,
                    'from' => $oRequest->from,
                    'to' => $oRequest->to,
                ]);
            }
        }

        return LocalResponse::returnMessage("request status updated");
    }
    public function responseToParkingRequest(ResponseToParkingOccupation $request)
    {
        $oRequest = GarageOccupationRequest::where('id', $request->request_id)->first();
        if ($oRequest->status != 'requested') {
            // this request has been already handled
            return LocalResponse::returnMessage("This request had been already handled");
        }
        $oRequest->status = $request->status;
        $parkingSpace = ParkingSpace::where('id', $oRequest->parking_space_id)->first();
        // check if this employee belongs to this room's hotel
        $hotel = $parkingSpace->garage->hotel;
        $employee = HotelEmployee::where('employee_id', Auth::user()->id)->first();
        if ($employee->hotel_id != $hotel->id) {
            return LocalResponse::returnMessage("you don't belongs to this hotel .", 403);
        }
        $customer = $oRequest->customer;

        if ($parkingSpace->is_occupied) {
            // you can't approve this request because room is occupied
            // if you send stauts equal approved we will rejected .
            $oRequest->status = 'rejected';
            $oRequest->save();
            $customer->notify(new ResponseToOccupationNotification($oRequest->status, $oRequest->id));
            return LocalResponse::returnMessage("this room is occupied", 406);
        } else {
            $oRequest->save();
            $customer->notify(new ResponseToOccupationNotification($oRequest->status, $oRequest->id));
            if ($oRequest->status == 'approved') {
                CustomerParking::create([
                    'parking_id' => $oRequest->room_id,
                    'customer_id' => $oRequest->customer_id,
                    'from' => $oRequest->from,
                    'to' => $oRequest->to,
                ]);
            }
        }
        return LocalResponse::returnMessage("request status updated");
    }
}

<?php

namespace App\Http\Controllers\employee;

use App\Models\Room;
use App\Models\MeetingRoom;
use App\Models\CustomerRoom;
use App\Models\ParkingSpace;
use Illuminate\Http\Request;
use App\Models\HotelEmployee;
use App\Models\CustomerParking;
use App\Http\Traits\LocalResponse;
use App\Models\MeetingRoomCustomer;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\RoomOccupationRequest;
use App\Models\GarageOccupationRequest;
use App\Models\MeetingRoomOccupationRequest;
use App\Notifications\ResponseToOccupationNotification;
use App\Http\Requests\employee\ResponseToOccupationRequest;
use App\Http\Requests\employee\ResponseToParkingOccupation;
use App\Notifications\ResponseToMeetingOccupationNotification;
use App\Notifications\ResponseToParkingOccupationNotification;
use App\Http\Requests\employee\ResponseToMeetingRoomOccupation;

class OccupationController extends Controller
{
    public function responseToRequest(ResponseToOccupationRequest $request)
    {
        $oRequest = RoomOccupationRequest::where('id', $request->request_id)->first();

        $rooms = Room::where('type', $oRequest->room_type)->get()->filter(function ($room) {
            return !$room->is_occupied;
        });
        if (count($rooms) == 0) {
            return LocalResponse::returnMessage("there is no available room in this type right now.");
        }

        if ($oRequest->status != 'requested') {
            // this request has been already handled
            return LocalResponse::returnMessage("This request had been already handled");
        }
        $room = $rooms->first();
        $oRequest->status = $request->status;
        $customer = $oRequest->customer;
        if ($room->is_occupied) {
            // you can't approve this request because room is occupied
            // if you send stauts equal approved we will rejected .
            $oRequest->status = 'rejected';
            $oRequest->save();
            $customer->notify(new ResponseToOccupationNotification($oRequest->status, $oRequest->id));
            return LocalResponse::returnMessage("this room is occupied", 406);
        } else {
            $oRequest->price = $room->price;
            $oRequest->save();
            $customer->notify(new ResponseToOccupationNotification($oRequest->status, $oRequest->id));
            if ($oRequest->status == 'approved') {
                CustomerRoom::create([
                    'room_id' => $room->id,
                    'customer_id' => $oRequest->customer_id,
                    'from' => $oRequest->from,
                    'to' => $oRequest->to,
                ]);
            }
        }

        return LocalResponse::returnData('room', Room::find($room->id), "request status updated");
    }
    public function responseToParkingRequest(ResponseToParkingOccupation $request)
    {
        $oRequest = GarageOccupationRequest::where('id', $request->request_id)->first();
        if ($oRequest->status != 'requested') {
            // this request has been already handled
            return LocalResponse::returnMessage("This request had been already handled");
        }
        $oRequest->status = $request->status;
        $customer = $oRequest->customer;
        $spaces = ParkingSpace::get()->filter(fn ($space) => !$space->is_occupied);
        if (count($spaces) == 0) {
            $customer->notify(new ResponseToParkingOccupationNotification($oRequest->status, $oRequest->id));
            return LocalResponse::returnMessage('There Is No Parking Space available');
        }
        $parkingSpace = $spaces->first();
        // $parkingSpace = ParkingSpace::where('id', $oRequest->parking_space_id)->first();
        // check if this employee belongs to this room's hotel
        // $hotel = $parkingSpace->garage->hotel;
        // $employee = HotelEmployee::where('employee_id', Auth::user()->id)->first();
        // if ($employee->hotel_id != $hotel->id) {
        //     return LocalResponse::returnMessage("you don't belongs to this hotel .", 403);
        // }

        $oRequest->save();
        $customer->notify(new ResponseToParkingOccupationNotification($oRequest->status, $oRequest->id));
        if ($oRequest->status == 'approved') {
            CustomerParking::create([
                'parking_id' => $parkingSpace->id,
                'customer_id' => $oRequest->customer_id,
                'from' => $oRequest->from,
                'to' => $oRequest->to,
            ]);
        }
        return LocalResponse::returnData('space', ParkingSpace::find($parkingSpace->id), "request status updated");
    }
    public function responseToMeetingRequest(ResponseToMeetingRoomOccupation $request)
    {
        $oRequest = MeetingRoomOccupationRequest::where('id', $request->request_id)->first();
        if ($oRequest->status != 'requested') {
            // this request has been already handled
            return LocalResponse::returnMessage("This request had been already handled");
        }
        $customer = $oRequest->customer;
        $rooms = MeetingRoom::where('type', $oRequest->type)->get()->filter(function ($room) {
            return !$room->is_occupied;
        })->values();
        if (count($rooms) == 0) {
            $customer->notify(new ResponseToMeetingOccupationNotification($oRequest->status, $oRequest->id));
            return LocalResponse::returnMessage("there is no available meeting room in this type right now.");
        }
        $room = $rooms->first();
        $oRequest->status = $request->status;
        $oRequest->price = $room->price;
        $oRequest->save();
        $customer->notify(new ResponseToMeetingOccupationNotification($oRequest->status, $oRequest->id));
        if ($oRequest->status == 'approved') {
            MeetingRoomCustomer::create([
                'room_id' => $room->id,
                'customer_id' => $oRequest->customer_id,
                'from' => $oRequest->from,
                'to' => $oRequest->to,
            ]);
        }


        return LocalResponse::returnData('meeting_room', MeetingRoom::find($room->id), "request status updated");
    }
    public function getAll()
    {
        $roomRequests = RoomOccupationRequest::get()->filter(fn ($item) => $item->is_request)->values();
        $parkingRequests = GarageOccupationRequest::get()->filter(fn ($item) => $item->is_request)->values();
        $meetingRoomRequests = MeetingRoomOccupationRequest::get()->filter(fn ($item) => $item->is_request)->values();
        return LocalResponse::returnData('requests', [
            'room' => $roomRequests,
            'meeting_room' => $meetingRoomRequests,
            'park' => $parkingRequests
        ]);
    }
}

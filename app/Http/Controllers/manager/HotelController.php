<?php

namespace App\Http\Controllers\manager;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Traits\LocalResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\manager\AddNewEmployeeRequest;
use App\Models\Hotel;
use App\Models\HotelEmployee;
use Illuminate\Support\Facades\Auth;

class HotelController extends Controller
{
    public function myHotel()
    {
        // select users.* , hotels.* , rooms.* from users inner join hotels on users.id = hotels.manager_id
        // inner join rooms on hotels.id = rooms.hotel_id where users.id = 3
        // $hotel = Hotel::where("manager_id",Auth::user()->id)->with('rooms')->first();
        $user = User::where('id', Auth::user()->id)->with('hotel.rooms')->first();
        return LocalResponse::returnData('hotel', $user->hotel);
    }
    public function addNewEmployee(AddNewEmployeeRequest $request)
    {
        $employee = User::create($request->values());
        // insert into users (email,password,name,type) values (?,?,?,?)
        $hotel_emp = HotelEmployee::create([
            'employee_id' => $employee->id,
            'hotel_id' => Auth::user()->hotel->id,
        ]);
        return LocalResponse::returnData('emp', $employee, 'employee created successfully.');
    }
}

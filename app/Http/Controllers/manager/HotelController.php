<?php

namespace App\Http\Controllers\manager;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Traits\LocalResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\manager\AddNewEmployeeRequest;
use App\Models\HotelEmployee;
use Illuminate\Support\Facades\Auth;

class HotelController extends Controller
{
    public function myHotel()
    {
        $user = User::where('id', Auth::user()->id)->with('hotel.rooms')->first();
        return LocalResponse::returnData('hotel', $user->hotel);
    }
    public function addNewEmployee(AddNewEmployeeRequest $request)
    {
        $employee = User::create($request->values());
        $hotel_emp = HotelEmployee::create([
            'employee_id' => $employee->id,
            'hotel_id' => Auth::user()->hotel->id,
        ]);
        return LocalResponse::returnData('emp', $employee, 'employee created successfully.');
    }
}

<?php

namespace App\Http\Controllers\manager;

use Exception;
use App\Models\Room;
use App\Models\RoomImage;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Helpers\FolderHelper;
use App\Http\Traits\LocalResponse;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\manager\AddNewRoomRequest;
use App\Http\Requests\manager\AddNewMeetingRoomRequest;
use App\Models\MeetingRoom;
use App\Models\MeetingRoomImages;

class RoomController extends Controller
{
    public function addNewRoom(AddNewRoomRequest $request)
    {
        $room = new Room;
        try {
            DB::beginTransaction();
            $room = Room::create($request->values());
            if ($request->hasFile('images')) {
                $index = 0;
                $hotel = Auth::user()->hotel;
                $images = $request->file('images');
                foreach ($images as $image) {
                    $slug = Str::slug("$hotel->name" . '-' . $room->number); // new room => new%20room => new-hotel-21
                    $name = time() + $index++;
                    $image_name = FolderHelper::uploadFileOnPublic($image, "images/rooms/$slug", $name);
                    RoomImage::create([
                        'room_id' => $room->id,
                        'file_name' => $image_name
                    ]);
                }
            }
            DB::commit();
            return LocalResponse::returnData("data", $room);
        } catch (Exception $e) {
            DB::rollBack();
            return LocalResponse::returnMessage("can't add this room ", $e->getMessage());
        }
    }
    public function addNewMeetingRoom(AddNewMeetingRoomRequest $request)
    {
        $room = new MeetingRoom;
        try {
            DB::beginTransaction();
            $room = MeetingRoom::create($request->values());
            if ($request->hasFile('images')) {
                $index = 0;
                $hotel = Auth::user()->hotel;
                $images = $request->file('images');
                foreach ($images as $image) {
                    $slug = Str::slug("$hotel->name" . '-meeting-' . $room->id); // new room => new%20room => new-hotel-21
                    $name = time() + $index++;
                    $image_name = FolderHelper::uploadFileOnPublic($image, "images/rooms/$slug", $name);
                    MeetingRoomImages::create([
                        'room_id' => $room->id,
                        'file_name' => $image_name
                    ]);
                }
            }
            DB::commit();
            return LocalResponse::returnData("data", $room);
        } catch (Exception $e) {
            DB::rollBack();
            return LocalResponse::returnMessage("can't add this room ", $e->getMessage());
        }
    }
}

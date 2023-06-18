<?php

namespace App\Http\Helpers;

use App\Models\Hotel;
use Illuminate\Support\Str;
use Illuminate\Http\UploadedFile;

class FolderHelper
{

    public static function uploadFileOnPublic(UploadedFile $file, $filePath = "img/uploads", $name = "", $prefix = "_image")
    {
        if (empty($file)) return "";
        $filename = $prefix . $name . "." . $file->getClientOriginalExtension();
        $file->move(public_path($filePath), $filename);
        return $filename;
    }
    public static function getRoomImages($hotelName, $roomNumber, $imagesName = [])
    {
        $http = request()->getSchemeAndHttpHost();
        $slug = Str::slug("$hotelName" . '-' . $roomNumber);
        $path = "images/rooms/$slug/";
        $imagesURL = [];
        foreach ($imagesName as $image) {
            $imagesURL[] = $http . '/public/' . $path . $image->file_name;
        }
        return $imagesURL;
    }
    public static function getMeetingRoomImages($hotelName, $room_id, $imagesName = [])
    {
        $http = request()->getSchemeAndHttpHost();
        $slug = Str::slug("$hotelName" . '-meeting-' . $room_id);
        $path = "images/rooms/$slug/";
        $imagesURL = [];
        foreach ($imagesName as $image) {
            $imagesURL[] = $http . '/public/' . $path . $image->file_name;
        }
        return $imagesURL;
    }
}

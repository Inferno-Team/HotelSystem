<?php

namespace App\Http\Controllers\manager;

use Illuminate\Http\Request;
use App\Http\Traits\LocalResponse;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function getAll()
    {
        return LocalResponse::returnData('notifications', Auth::user()->notifications);
    }
    public function getAllUnRead()
    {
        return LocalResponse::returnData('notifications', Auth::user()->unreadNotifications);
    }
    public function getUnReadNotification($id)
    {
        $user = Auth::user();

        foreach ($user->unreadNotifications as $notification) {
            if ($notification->id == $id)
                return LocalResponse::returnData('notification', $notification);
        }
        return LocalResponse::returnData('notification', (object)[]);
    }
    public function getNotification($id)
    {
        $user = Auth::user();

        foreach ($user->notifications as $notification) {
            if ($notification->id == $id)
                return LocalResponse::returnData('notification', $notification);
        }
        return LocalResponse::returnData('notification', (object)[]);
    }
    public function readAll()
    {
        Auth::user()->unreadNotifications->markAsRead();
        return LocalResponse::returnMessage('all notifications has marked as read');
    }
    public function read($id)
    {
        $user = Auth::user();
        foreach ($user->unreadNotifications as $notification) {
            if ($notification->id == $id) {
                $notification->markAsRead();
                return LocalResponse::returnMessage('notification has marked as read');
            }
        }
        return LocalResponse::returnMessage('notification not found.');
    }
}

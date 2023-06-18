<?php

use App\Http\Controllers\customer\FavController;
use App\Http\Controllers\customer\HotelController;
use App\Http\Controllers\customer\RequestController;
use App\Http\Controllers\employee\OccupationController;
use App\Http\Controllers\manager\NotificationController;
use Illuminate\Http\Request;
use App\Http\Traits\LocalResponse;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UtilesController;
use App\Http\Controllers\manager\RoomController;
use App\Http\Requests\customer\OccupationRequest;
use App\Models\CustomerParking;
use App\Models\CustomerRoom;
use App\Models\GarageOccupationRequest;
use App\Models\RoomOccupationRequest;

Route::post('/login', [UtilesController::class, 'login']);
Route::post('/register', [UtilesController::class, 'register']);
Route::get('/404', fn () => LocalResponse::returnMessage('not logged in yet.', 401))->name('login');
Route::group(['middleware' => ['auth:sanctum']], function () {

    Route::get('/notifications', [NotificationController::class, 'getAll']);
    Route::get('/notifications/{id}', [NotificationController::class, 'getNotification']);
    Route::post('/read/notifications', [NotificationController::class, 'readAll']);
    Route::post('/read/notifications/{id}', [NotificationController::class, 'read']);
    Route::get('/unread/notifications', [NotificationController::class, 'getAllUnRead']);
    Route::get('/unread/notifications/{id}', [NotificationController::class, 'getUnReadNotification']);
    Route::get('/user', fn (Request $request) => $request->user());

    Route::group(['middleware' => ['is_manager']], function () {

        Route::get('/my-hotel', [\App\Http\Controllers\manager\HotelController::class, 'myHotel']);
        Route::post('/add-new-employee', [\App\Http\Controllers\manager\HotelController::class, 'addNewEmployee']);
        Route::post('/add_new_room', [RoomController::class, 'addNewRoom']);
        Route::post('/add_new_meeting_room', [RoomController::class, 'addNewMeetingRoom']);
        Route::get('/remove_data', function () {
            GarageOccupationRequest::truncate();
            CustomerParking::truncate();
            CustomerRoom::truncate();
            RoomOccupationRequest::truncate();
        });
    });
    Route::group(['middleware' => ['is_customer']], function () {
        Route::get('/hotels', [HotelController::class, 'getAll']);
        Route::get('/hotels/{id}', [HotelController::class, 'getHotel']);
        Route::post('/request-occupation-room', [RequestController::class, 'requestOccupationRoom']);

        Route::post('/add-to-fav', [FavController::class, 'addToFav']);
        Route::post('/remove-from-fav', [FavController::class, 'reomveFromFav']);
        Route::get('/get-my-fav', [FavController::class, 'getMyFav']);

        Route::get('/get-my-booking', [HotelController::class, 'getMyReservations']);
        Route::get('/get-hotel-parking-spaces/{id}', [HotelController::class, 'getHotelParkingSpace']);
        Route::post('/check-garage', [HotelController::class, 'checkGarage']);
        Route::post('/request-occupation-garage', [RequestController::class, 'requestOccupationGarage']);
        Route::post('/request-occupation-meeting-room', [RequestController::class, 'requestOccupationMeetingRoom']);
    });
    Route::group(['middleware' => ['is_employee']], function () {
        Route::get('/get-all-requests', [OccupationController::class, 'getAll']);
        Route::post('/response-to-occupation-request', [OccupationController::class, 'responseToRequest']);
        Route::post('/response-to-parking-occupation-request', [OccupationController::class, 'responseToParkingRequest']);
        Route::post('/response-to-meeting-occupation-request', [OccupationController::class, 'responseToMeetingRequest']);
    });
});

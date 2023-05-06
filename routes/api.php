<?php

use App\Http\Controllers\customer\HotelController;
use App\Http\Controllers\customer\RequestController;
use App\Http\Controllers\employee\OccupationController;
use App\Http\Controllers\manager\NotificationController;
use Illuminate\Http\Request;
use App\Http\Traits\LocalResponse;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UtilesController;
use App\Http\Controllers\manager\RoomController;


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


    Route::group(['middleware' => ['is_manager']], function () {
        Route::get('/user', fn (Request $request) => $request->user());
        Route::get('/my-hotel', [\App\Http\Controllers\manager\HotelController::class, 'myHotel']);
        Route::post('/add-new-employee', [\App\Http\Controllers\manager\HotelController::class, 'addNewEmployee']);
        Route::post('/add_new_room', [RoomController::class, 'addNewRoom']);
    });
    Route::group(['middleware' => ['is_customer']], function () {
        Route::get('/hotels', [HotelController::class, 'getAll']);
        Route::get('/hotels/{id}', [HotelController::class, 'getHotel']);
        Route::post('/request-occupation', [RequestController::class, 'requestOccupation']);
    });
    Route::group(['middleware' => ['is_employee']], function () {
        Route::post('/response-to-occupation-request',[OccupationController::class,'responseToRequest']);
    });
});

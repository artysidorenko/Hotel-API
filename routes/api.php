<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
 */

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('rooms', 'RoomController@list');
Route::get('room/{id}', 'RoomController@get')
    ->where('id', '[0-9]+');
Route::post('room/{id}', 'RoomController@update')
    ->middleware('prevalidate:beds,floor,available,price');
Route::post('/new/room', 'RoomController@new')
    ->middleware('prevalidate:beds,floor,available,price');

Route::get('guests', 'GuestController@list');
Route::get('guest/{id}', 'GuestController@get')
    ->where('id', '[0-9]+');
Route::post('guest/{id}', 'GuestController@update')
    ->middleware('prevalidate:name,surname,age');
Route::post('new/guest', 'GuestController@new')
    ->middleware('prevalidate:name,surname,age');

Route::get('bookings', 'BookingController@list');
Route::get('booking/{id}', 'BookingController@get')
    ->where('id', '[0-9]+');
Route::post('booking/{id}', 'BookingController@update')
    ->middleware('prevalidate:room_id,arrival,departure,guest_id');
Route::post('new/booking', 'BookingController@new')
    ->middleware('prevalidate:room_id,arrival,departure,guest_id');

Route::fallback(function () {
    return response()->json(['error' => 'Not Found.'], 404);
})->name('api.fallback.404');

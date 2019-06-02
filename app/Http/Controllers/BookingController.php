<?php

namespace App\Http\Controllers;

use App\Booking;
use App\Guest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Request;

class BookingController extends Controller
{
    /**
     * List all current booking information.
     *
     * @return json
     */
    function list() {

        return Booking::all();
    }

    /**
     * List current booking information for specified id.
     *
     * @return json
     */
    public function get($id)
    {
        return Booking::findOrFail($id);
    }

    /**
     * Create New booking (id autoincrements).
     *
     * @return json
     */
    function new (Request $request) {

        $acceptedFields = ['room_id', 'arrival', 'departure', 'guest_id'];
        $trimRequest = $request->only($acceptedFields);

        if ($trimRequest != $request->all()) {
            return response()->json([
                'error' => 'one or more invalid or extraneous fields were included in the request',
                'accepted fields list' => $acceptedFields,
            ], 422);
        }

        $validator = Validator::make($request->all(), [
            'room_id' => 'required|exists:rooms,id',
            'arrival' => 'required|date|before:departure',
            'departure' => 'required|date|after:arrival',
            'guest_id' => 'required|exists:guests,id',
        ]);

        if ($validator->fails()) {
            $error = $validator->messages();
            return response()->json($error, 422);
        }

        $input = $request->all();
        $input['arrival'] = Carbon::parse($input['arrival']);
        $input['departure'] = Carbon::parse($input['departure']);
        $booking = Booking::create($input);

        $guest = Guest::findOrFail($input['guest_id']);
        $guest->update([
            'room_id' => $input['room_id'],
        ]);
        $guest->push();

        return response()->json($booking, 200);
    }

    /**
     * Update Booking Information (exc id).
     *
     * @return json
     */
    public function update(Request $request, $id)
    {
        if (!is_numeric($id)) {
            return response()->json([
                'error' => 'Not Found.',
            ], 404);
        }

        $acceptedFields = ['room_id', 'arrival', 'departure', 'guest_id'];
        $trimRequest = $request->only($acceptedFields);

        if ($trimRequest != $request->all()) {
            return response()->json([
                'error' => 'one or more invalid or extraneous fields were included in the request',
                'accepted fields list' => $acceptedFields,
            ], 422);
        }

        $validator = Validator::make($request->all(), [
            'room_id' => 'exists:rooms,id',
            'arrival' => 'date|before:departure',
            'departure' => 'date|after:arrival',
            'guest_id' => 'exists:guests,id',
        ]);

        if ($validator->fails()) {
            $error = $validator->messages();
            return response()->json($error, 422);
        }

        $input = $request->all();
        $input['arrival'] = Carbon::parse($input['arrival']);
        $input['departure'] = Carbon::parse($input['departure']);

        $booking = Booking::findOrFail($id);
        $guestPre = $booking->guest_id;
        $roomPre = $booking->room_id;
        $booking->update($input);
        $booking->push();

        if ($booking->guest_id != $guestPre) {
            $guestPre = Guest::findOrFail($guestPre);
            $guestPre->update([
                'room_id' => null,
            ]);
            $guestPre->push();

            $guestPost = Guest::findOrFail($booking->guest_id);
            $guestPost->update([
                'room_id' => $booking->room_id,
            ]);
            $guestPost->push();
        } else if ($booking->room_id != $roomPre) {
            $guest = Guest::findOrFail($booking->guest_id);
            $guest->update([
                'room_id' => $booking->room_id,
            ]);
            $guest->push();
        }

        return response()->json($booking, 200);
    }
}
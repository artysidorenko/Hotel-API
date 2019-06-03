<?php

namespace App\Http\Controllers;

use App\Guest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Request;
use App\Booking;

use Illuminate\Support\Facades\Log;

class GuestController extends Controller
{
    /**
     * Refresh guest database by checking if they're staying in a room on today's date
     *
     * @return void
     */
    public function refreshRoomId()
    {
        $now = new \DateTime();

        $guests = Guest::all();
        $bookings = Booking::all();
        foreach ($guests as $guest) {
            $guest->room_id = null;
            foreach ($bookings as $booking) {
                if ($guest->id === $booking->guest_id && $now > $booking->arrival && $now < $booking->departure) {
                    $guest->room_id = $booking->room_id;
                }
            }
            $guest->push();
        }
    }

    /**
     * List all current guest information.
     *
     * @return json
     */
    function list() {
        
        // First refresh guest database with current rooms
        $this->refreshRoomId();

        return Guest::all();
    }

    /**
     * List current guest information for specified id.
     *
     * @return json
     */
    public function get($id)
    {
        return Guest::findOrFail($id);
    }

    /**
     * Create New guest (id autoincrements).
     *
     * @return json
     */
    function new (Request $request) {
        Log::debug('NEW controller hit');
        $validator = Validator::make($request->all(), [
            'surname' => 'required|alpha',
            'name' => 'required|alpha',
            'age' => 'required|integer|between:1,120',
        ]);

        if ($validator->fails()) {
            $error = $validator->messages();
            return response()->json($error, 422);
        }

        $input = $request->all();
        $guest = Guest::create($input);
        $guest->push();

        return response()->json($guest, 200);
    }

    /**
     * Update Guest Information (exc id).
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

        $acceptedFields = ['surname', 'name', 'age', 'room_id'];
        $trimRequest = $request->only($acceptedFields);

        if ($trimRequest != $request->all()) {
            return response()->json([
                'error' => 'one or more invalid or extraneous fields were included in the request',
                'accepted fields list' => $acceptedFields,
            ], 422);
        }

        $validator = Validator::make($request->all(), [
            'surname' => 'alpha',
            'name' => 'alpha',
            'age' => 'integer|between:1,120',
            'room_id' => 'exists:rooms,id',
        ]);

        if ($validator->fails()) {
            $error = $validator->messages();
            return response()->json($error, 422);
        }

        $input = $request->all();
        $guest = Guest::findOrFail($id);
        $guest->update($input);
        $guest->push();

        return response()->json($guest, 200);
    }
}

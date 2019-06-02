<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Room;
use App\Booking;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Request;
use Illuminate\Support\Facades\Log;

class RoomController extends Controller
{
    /**
     * Refresh room database by checking if it's free on today's date
     *
     * @return void
     */
    public function refreshOccupancy()
    {
        $now = new \DateTime();

        $rooms = Room::all();
        $bookings = Booking::all();
        foreach ($rooms as $room) {
            $room->available = true;
            foreach ($bookings as $booking) {
                if ($room->id === $booking->room_id && $now > $booking->arrival && $now < $booking->departure) {
                    $room->available = false;
                }
            }
            $room->push();
        }
    }

    /**
     * List all current room information, filtered by 'free'/'available' if specified.
     *
     * @return json
     */
    function list(Request $request) {

        // First refresh room database by checking if it's free on today's date
        $this->refreshOccupancy();

        if ($request->query('free')) {
            return Room::where('available', $request->query('free') == 'true')->get();
        }

        return Room::all();
    }

    /**
     * List current room information for specified room.
     *
     * @return json
     */
    public function get($id)
    {
        return Room::findOrFail($id);
    }

    /**
     * Create New Room (id autoincrements).
     *
     * @return json
     */
    function new (Request $request) {

        $validator = Validator::make($request->all(), [
            'beds' => 'required|integer|between:1,4',
            'floor' => 'required|integer|between:1,6',
            'price' => 'required|integer|between:0,200',
            'available' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            $error = $validator->messages();
            return response()->json($error, 422);
        }

        $input = $request->all();
        $room = Room::create($input);
        $room->push();

        return response()->json($room, 200);
    }

    /**
     * Update Room Information (exc id).
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

        $validator = Validator::make($request->all(), [
            'beds' => 'integer|between:1,4',
            'floor' => 'integer|between:1,6',
            'price' => 'integer',
            'available' => 'boolean',
        ]);

        if ($validator->fails()) {
            $error = $validator->messages();
            return response()->json($error, 422);
        }

        $input = $request->all();

        $room = Room::findOrFail($id);
        $room->update($input);
        $room->push();

        return response()->json($room, 200);
    }
}

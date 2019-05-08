<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Room;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Request;
use Hamcrest\Type\IsInteger;

class RoomController extends Controller
{
    /**
     * List all current room information, filtered by 'free'/'available' if specified.
     *
     * @return json
     */
    function list(Request $request) {

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

        if(!is_numeric($id)) {
            return response()->json([
            'error' =>'Not Found.',
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

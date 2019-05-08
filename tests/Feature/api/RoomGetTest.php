<?php

namespace Tests\Feature\api;

use App\Room;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Tests\TestCase;

class RoomGetTest extends TestCase
{
    /** @test */
    public function requesting_an_invalid_room_triggers_model_not_found_exception()
    {
        $this->withoutExceptionHandling();

        $this->expectException(ModelNotFoundException::class);
        $this->json('GET', '/api/room/999');
    }

    /** @test */
    public function requesting_an_invalid_room_triggers_valid_exception_message()
    {
        $this->withoutExceptionHandling();

        try {
            $this->json('GET', '/api/room/999');
        } catch (ModelNotFoundException $exception) {
            $this->assertEquals('No query results for model [App\Room] 999', $exception->getMessage());
            return;
        }

        $this->fail('ModelNotFoundException should be triggered.');
    }

    /** @test */
    public function requesting_an_invalid_room_returns_no_query_results_error()
    {
        $response = $this->json('GET', '/api/room/999');
        $response->assertStatus(404);
        $response->assertHeader('Content-Type', 'application/json');
        $response->assertJson([
            'message' => 'No query results for model [App\Room] 999',
        ]);
    }

    /** @test */
    public function invalid_format_room_uri_triggers_fallback_route()
    {
        $response = $this->json('GET', '/api/room/invalid-room-id');
        $response->assertStatus(404);
        $response->assertHeader('Content-Type', 'application/json');
        $response->assertJson([
            'error' => 'Not Found.',
        ]);
    }

    /** @test */
    public function valid_request_to_room_id_endpoint_generates_valid_json_response()
    {
        $response = $this->json('GET', '/api/room/5');
        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/json');
        $response->assertJsonCount(5);
        $response->assertJson([
            'id' => 5,
        ])
            ->assertJsonStructure([
                '*' => array_keys((new Room())->toArray()),
            ])
            ->assertJsonStructure([
                '*' => 'id',
                '*' => 'floor',
                '*' => 'beds',
                '*' => 'price',
                '*' => 'available'
            ]);
    }

    /** @test */
    public function valid_request_to_rooms_endpoint_generates_valid_json_list_response()
    {
        $response = $this->json('GET', '/api/rooms');
        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/json');
        $response->assertJsonCount(5, '0.*');
        $response->assertJsonFragment([
            'id' => 5
        ]);
    }
}

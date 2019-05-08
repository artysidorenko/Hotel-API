<?php

namespace Tests\Feature\api;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class RoomEditTest extends TestCase
{
    use DatabaseTransactions;

    static $post = [
        'beds' => 1,
        'floor' => 3,
        'price' => 99,
        'available' => true,
    ];

    /** @test */
    public function posting_to_an_invalid_room_triggers_model_not_found_exception()
    {
        $this->withoutExceptionHandling();

        $this->expectException(ModelNotFoundException::class);
        $this->json('POST', '/api/room/999', RoomEditTest::$post);
    }

    /** @test */
    public function requesting_an_invalid_room_triggers_valid_exception_message()
    {
        $this->withoutExceptionHandling();

        try {
            $this->json('POST', '/api/room/999', RoomEditTest::$post);
        } catch (ModelNotFoundException $exception) {
            $this->assertEquals('No query results for model [App\Room] 999', $exception->getMessage());
            return;
        }

        $this->fail('ModelNotFoundException should be triggered.');
    }

    /** @test */
    public function requesting_an_invalid_room_returns_no_query_results_error()
    {
        $response = $this->json('POST', '/api/room/999', RoomEditTest::$post);
        $response->assertStatus(404);
        $response->assertHeader('Content-Type', 'application/json');
        $response->assertJson([
            'message' => 'No query results for model [App\Room] 999',
        ]);
    }

    /** @test */
    public function invalid_format_room_uri_triggers_fallback_route()
    {
        $response = $this->json('POST', '/api/room/invalid-room-id', RoomEditTest::$post);
        $response->assertStatus(404);
        $response->assertHeader('Content-Type', 'application/json');
        $response->assertJson([
            'error' => 'Not Found.',
        ]);
    }

    /** @test */
    public function posting_empty_body_should_return_error()
    {
        $response = $this->json('POST', '/api/room/5');
        $response
            ->assertStatus(400)
            ->assertJson([
                'error' => 'post request body cannot be empty',
            ]);

    }

    /** @test */
    public function posting_invalid_field_should_return_error()
    {
        $post = [
            'beds' => 1,
            'floor' => 3,
            'price' => 100,
            'wrong_field' => null,
        ];
        $response = $this->json('POST', '/api/room/5', $post);
        $response
            ->assertStatus(422)
            ->assertJsonFragment([
                'error' => 'one or more invalid or extraneous fields were included in the request'
            ]);

    }

    /** @test */
    public function posting_extraneous_field_should_return_error()
    {
        $post = [
            'beds' => 1,
            'floor' => 3,
            'available' => true,
            'price' => 100,
            'extraneous_field' => true
        ];
        $response = $this->json('POST', '/api/room/5', $post);
        $response
            ->assertStatus(422)
            ->assertJsonFragment([
                'error' => 'one or more invalid or extraneous fields were included in the request'
            ]);

    }

    /** @test */
    public function posting_invalid_value_should_return_error()
    {
        $post = [
            'beds' => 1,
            'floor' => 3,
            'price' => 100,
            'available' => 'blue'
        ];
        $response = $this->json('POST', '/api/room/5', $post);
        $response
            ->assertStatus(422)
            ->assertJsonFragment([
                'available' => ['The available field must be true or false.']
            ]);

    }

    /** @test */
    public function updating_valid_room_should_respond_with_room_details()
    {
        $post = [
            'beds' => 1,
            'floor' => 3,
            'price' => 99,
            'available' => true
        ];
        $response = $this->json('POST', '/api/room/5', $post);
        $response
            ->assertStatus(200)
            ->assertJsonFragment([
                'beds' => 1,
                'floor' => 3,
                'price' => 99,
                'available' => true
            ])
            ->assertJsonStructure([
                '*' => 'id',
                '*' => 'floor',
                '*' => 'beds',
                '*' => 'price',
                '*' => 'available'
            ]);

    }
}

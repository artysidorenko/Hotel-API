<?php

namespace Tests\Feature\api;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Tests\TestCase;

class GuestGetTest extends TestCase
{
    /** @test */
    public function requesting_an_invalid_guest_triggers_model_not_found_exception()
    {
        $this->withoutExceptionHandling();

        $this->expectException(ModelNotFoundException::class);
        $this->json('GET', '/api/guest/999');
    }

    /** @test */
    public function requesting_an_invalid_guest_triggers_valid_exception_message()
    {
        $this->withoutExceptionHandling();

        try {
            $this->json('GET', '/api/guest/999');
        } catch (ModelNotFoundException $exception) {
            $this->assertEquals('No query results for model [App\Guest] 999', $exception->getMessage());
            return;
        }

        $this->fail('ModelNotFoundException should be triggered.');
    }

    /** @test */
    public function requesting_an_invalid_guest_returns_no_query_results_error()
    {
        $response = $this->json('GET', '/api/guest/999');
        $response->assertStatus(404);
        $response->assertHeader('Content-Type', 'application/json');
        $response->assertJson([
            'message' => 'No query results for model [App\Guest] 999',
        ]);
    }

    /** @test */
    public function invalid_format_guest_uri_triggers_fallback_route()
    {
        $response = $this->json('GET', '/api/guest/invalid-Guest-id');
        $response->assertStatus(404);
        $response->assertHeader('Content-Type', 'application/json');
        $response->assertJson([
            'error' => 'Not Found.',
        ]);
    }

    /** @test */
    public function valid_request_to_guest_id_endpoint_generates_valid_json_response()
    {
        $response = $this->json('GET', '/api/guest/5');
        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/json');
        $response->assertJsonCount(5);
        $response->assertJson([
            'id' => 5,
        ])
            ->assertJsonStructure([
                '*' => 'id',
                '*' => 'surname',
                '*' => 'name',
                '*' => 'age',
                '*' => 'room_id'
            ]);
    }

    /** @test */
    public function valid_request_to_guests_endpoint_generates_valid_json_list_response()
    {
        $response = $this->json('GET', '/api/guests');
        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/json');
        $response->assertJsonCount(5, '0.*');
        $response->assertJsonFragment([
            'id' => 5
        ]);
    }
}

<?php

namespace Tests\Feature\api;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class GuestEditTest extends TestCase
{
    use DatabaseTransactions;

    static $post = [
        'name' => 'Arty',
        'surname' => 'Smith',
        'age' => 31,
    ];

    /** @test */
    public function posting_to_an_invalid_guest_triggers_model_not_found_exception()
    {
        $this->withoutExceptionHandling();

        $this->expectException(ModelNotFoundException::class);
        $this->json('POST', '/api/guest/999', GuestEditTest::$post);
    }

    /** @test */
    public function requesting_an_invalid_guest_triggers_valid_exception_message()
    {
        $this->withoutExceptionHandling();

        try {
            $this->json('POST', '/api/guest/999', GuestEditTest::$post);
        } catch (ModelNotFoundException $exception) {
            $this->assertEquals('No query results for model [App\Guest] 999', $exception->getMessage());
            return;
        }

        $this->fail('ModelNotFoundException should be triggered.');
    }

    /** @test */
    public function requesting_an_invalid_guest_returns_no_query_results_error()
    {
        $response = $this->json('POST', '/api/guest/999', GuestEditTest::$post);
        $response->assertStatus(404);
        $response->assertHeader('Content-Type', 'application/json');
        $response->assertJson([
            'message' => 'No query results for model [App\Guest] 999',
        ]);
    }

    /** @test */
    public function invalid_format_guest_uri_triggers_fallback_route()
    {
        $response = $this->json('POST', '/api/guest/invalid-guest-id', GuestEditTest::$post);
        $response->assertStatus(404);
        $response->assertHeader('Content-Type', 'application/json');
        $response->assertJson([
            'error' => 'Not Found.',
        ]);
    }

    /** @test */
    public function posting_empty_body_should_return_error()
    {
        $response = $this->json('POST', '/api/guest/5');
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
            'name' => 'Arty',
            'surname' => 'Smith',
            'wrong_field' => null,
        ];
        $response = $this->json('POST', '/api/guest/5', $post);
        $response
            ->assertStatus(422)
            ->assertJsonFragment([
                'error' => 'one or more invalid or extraneous fields were included in the request',
            ]);
    }

    /** @test */
    public function posting_extraneous_field_should_return_error()
    {
        $post = [
            'name' => 'Arty',
            'surname' => 'Smith',
            'age' => 31,
            'extraneous_field' => true,
        ];
        $response = $this->json('POST', '/api/guest/5', $post);
        $response
            ->assertStatus(422)
            ->assertJsonFragment([
                'error' => 'one or more invalid or extraneous fields were included in the request',
            ]);
    }

    /** @test */
    public function posting_invalid_value_should_return_error()
    {
        $post = [
            'name' => 'Arty',
            'surname' => 'Smith',
            'age' => 121,
        ];
        $response = $this->json('POST', '/api/guest/5', $post);
        $response
            ->assertStatus(422)
            ->assertJsonFragment([
                'age' => ['The age must be between 1 and 120.'],
            ]);
    }

    /** @test */
    public function updating_valid_Guest_should_respond_with_guest_details()
    {
        $post = [
            'name' => 'Arty',
            'surname' => 'Smith',
            'age' => 31,
        ];
        $response = $this->json('POST', '/api/guest/5', $post);
        $response
            ->assertStatus(200)
            ->assertJsonFragment([
                'name' => 'Arty',
                'surname' => 'Smith',
                'age' => 31
            ])
            ->assertJsonStructure([
                '*' => 'name',
                '*' => 'surname',
                '*' => 'age',
                '*' => 'room_id'
            ]);
    }
}

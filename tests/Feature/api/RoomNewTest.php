<?php

namespace Tests\Feature\api;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class RoomNewTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function posting_empty_body_should_return_error()
    {
        $response = $this->json('POST', '/api/new/room');
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
        $response = $this->json('POST', '/api/new/room', $post);
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
        $response = $this->json('POST', '/api/new/room', $post);
        $response
            ->assertStatus(422)
            ->assertJsonFragment([
                'error' => 'one or more invalid or extraneous fields were included in the request'
            ]);

    }

    /** @test */
    public function posting_missing_field_should_return_error()
    {
        $post = [
            'beds' => 1,
            'floor' => 3
        ];
        $response = $this->json('POST', '/api/new/room', $post);
        $response
            ->assertStatus(422)
            ->assertJsonFragment([
                'available' => ['The available field is required.']
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
        $response = $this->json('POST', '/api/new/room', $post);
        $response
            ->assertStatus(422)
            ->assertJsonFragment([
                'available' => ['The available field must be true or false.']
            ]);

    }

    /** @test */
    public function posting_valid_new_room_should_respond_with_room_details()
    {
        $post = [
            'beds' => 1,
            'floor' => 3,
            'price' => 99,
            'available' => true
        ];
        $response = $this->json('POST', '/api/new/room', $post);
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

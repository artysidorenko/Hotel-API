<?php

namespace Tests\Feature\api;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class GuestNewTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function posting_empty_body_should_return_error()
    {
        $response = $this->json('POST', '/api/new/guest');
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
        $response = $this->json('POST', '/api/new/guest', $post);
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
        $response = $this->json('POST', '/api/new/guest', $post);
        $response
            ->assertStatus(422)
            ->assertJsonFragment([
                'error' => 'one or more invalid or extraneous fields were included in the request',
            ]);
    }

    /** @test */
    public function posting_missing_field_should_return_error()
    {
        $post = [
            'name' => 'Arty',
            'surname' => 'Smith',
        ];
        $response = $this->json('POST', '/api/new/guest', $post);
        $response
            ->assertStatus(422)
            ->assertJsonFragment([
                'age' => ['The age field is required.'],
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
        $response = $this->json('POST', '/api/new/guest', $post);
        $response
            ->assertStatus(422)
            ->assertJsonFragment([
                'age' => ['The age must be between 1 and 120.'],
            ]);
    }

    /** @test */
    public function posting_valid_new_guest_should_respond_with_guest_details()
    {
        $post = [
            'name' => 'Arty',
            'surname' => 'Smith',
            'age' => 31,
        ];
        $response = $this->json('POST', '/api/new/guest', $post);
        $response
            ->assertStatus(200)
            ->assertJsonFragment([
                'name' => 'Arty',
                'surname' => 'Smith',
                'age' => 31,
                'room_id' => null,
            ])
            ->assertJsonStructure([
                '*' => 'name',
                '*' => 'surname',
                '*' => 'age',
                '*' => 'room_id'
            ]);
    }
}

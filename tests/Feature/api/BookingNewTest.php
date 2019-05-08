<?php

namespace Tests\Feature\api;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class BookingNewTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function posting_empty_body_should_return_error()
    {
        $response = $this->json('POST', '/api/new/booking');
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
            'room_id' => 5,
            'arrival' => '2019/05/05',
            'departure' => '2019/05/09',
            'invalid_field' => 1,
        ];
        $response = $this->json('POST', '/api/new/booking', $post);
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
            'room_id' => 5,
            'arrival' => '2019/05/05',
            'departure' => '2019/05/09',
            'guest_id' => 1,
            'extraneous_field' => true,
        ];
        $response = $this->json('POST', '/api/new/booking', $post);
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
            'room_id' => 5,
            'arrival' => '2019/05/05',
            'departure' => '2019/05/09',
        ];
        $response = $this->json('POST', '/api/new/booking', $post);
        $response
            ->assertStatus(422)
            ->assertJsonFragment([
                'guest_id' => ['The guest id field is required.'],
            ]);
    }

    /** @test */
    public function posting_invalid_value_should_return_error()
    {
        $post = [
            'room_id' => 5,
            'arrival' => '2019/05/05',
            'departure' => '2019/05/09',
            'guest_id' => 999,
        ];
        $response = $this->json('POST', '/api/new/booking', $post);
        $response
            ->assertStatus(422)
            ->assertJsonFragment([
                'guest_id' => ['The selected guest id is invalid.'],
            ]);
    }

    /** @test */
    public function posting_valid_new_booking_should_respond_with_Booking_details()
    {
        $post = [
            'room_id' => 5,
            'arrival' => '2019/05/05',
            'departure' => '2019/05/09',
            'guest_id' => 1,
        ];
        $response = $this->json('POST', '/api/new/booking', $post);
        $response
            ->assertStatus(200)
            ->assertJsonFragment([
                'room_id' => 5,
                'arrival' => '2019/05/05',
                'departure' => '2019/05/09',
                'guest_id' => 1,
            ])
            ->assertJsonStructure([
                '*' => 'id',
                '*' => 'room_id',
                '*' => 'arrival',
                '*' => 'geparture',
                '*' => 'guest_id',
            ]);
    }

    /** @test */
    public function posting_valid_new_booking_should_populate_room_field_in_guest_table()
    {
        $post = [
            'room_id' => 5,
            'arrival' => '2019/05/05',
            'departure' => '2019/05/09',
            'guest_id' => 1,
        ];
        $this->json('POST', '/api/new/booking', $post);
        $response = $this->json('GET', '/api/guest/'.$post['guest_id']);
        $response->assertJson([
            'room_id' => 5,
        ]);

    }
}

<?php

namespace Tests\Feature\api;

use App\Booking;
use App\Guest;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class BookingEditTest extends TestCase
{
    use DatabaseTransactions;

    static $post = [
        'room_id' => 5,
        'arrival' => '2019/05/05',
        'departure' => '2019/05/09',
        'guest_id' => 1,
    ];

    /** @test */
    public function posting_to_an_invalid_booking_triggers_model_not_found_exception()
    {
        $this->withoutExceptionHandling();

        $this->expectException(ModelNotFoundException::class);
        $this->json('POST', '/api/booking/999', BookingEditTest::$post);
    }

    /** @test */
    public function requesting_an_invalid_booking_triggers_valid_exception_message()
    {
        $this->withoutExceptionHandling();

        try {
            $this->json('POST', '/api/booking/999', BookingEditTest::$post);
        } catch (ModelNotFoundException $exception) {
            $this->assertEquals('No query results for model [App\Booking] 999', $exception->getMessage());
            return;
        }

        $this->fail('ModelNotFoundException should be triggered.');
    }

    /** @test */
    public function requesting_an_invalid_booking_returns_no_query_results_error()
    {
        $response = $this->json('POST', '/api/booking/999', BookingEditTest::$post);
        $response->assertStatus(404);
        $response->assertHeader('Content-Type', 'application/json');
        $response->assertJson([
            'message' => 'No query results for model [App\Booking] 999',
        ]);
    }

    /** @test */
    public function invalid_format_booking_uri_triggers_fallback_route()
    {
        $response = $this->json('POST', '/api/booking/invalid-Booking-id', BookingEditTest::$post);
        $response->assertStatus(404);
        $response->assertHeader('Content-Type', 'application/json');
        $response->assertJson([
            'error' => 'Not Found.',
        ]);
    }

    /** @test */
    public function posting_empty_body_should_return_error()
    {
        $booking = Booking::create([
            'room_id' => 5,
            'arrival' => '2019/05/05',
            'departure' => '2019/05/09',
            'guest_id' => 1,
        ]);

        $response = $this->json('POST', '/api/booking/' . $booking->id);
        $response
            ->assertStatus(400)
            ->assertJson([
                'error' => 'post request body cannot be empty',
            ]);
    }

    /** @test */
    public function posting_invalid_field_should_return_error()
    {
        $booking = Booking::create([
            'room_id' => 5,
            'arrival' => '2019/05/05',
            'departure' => '2019/05/09',
            'guest_id' => 1,
        ]);

        $post = [
            'room_id' => 5,
            'arrival' => '2019/05/05',
            'departure' => '2019/05/09',
            'invalid_field' => 1,
        ];
        $response = $this->json('POST', '/api/booking/' . $booking->id, $post);
        $response
            ->assertStatus(422)
            ->assertJsonFragment([
                'error' => 'one or more invalid or extraneous fields were included in the request',
            ]);
    }

    /** @test */
    public function posting_extraneous_field_should_return_error()
    {
        $booking = Booking::create([
            'room_id' => 5,
            'arrival' => '2019/05/05',
            'departure' => '2019/05/09',
            'guest_id' => 1,
        ]);

        $post = [
            'room_id' => 5,
            'arrival' => '2019/05/05',
            'departure' => '2019/05/09',
            'guest_id' => 1,
            'extraneous_field' => true,
        ];
        $response = $this->json('POST', '/api/booking/' . $booking->id, $post);
        $response
            ->assertStatus(422)
            ->assertJsonFragment([
                'error' => 'one or more invalid or extraneous fields were included in the request',
            ]);
    }

    /** @test */
    public function posting_invalid_value_should_return_error()
    {
        $booking = Booking::create([
            'room_id' => 5,
            'arrival' => '2019/05/05',
            'departure' => '2019/05/09',
            'guest_id' => 1,
        ]);

        $post = [
            'room_id' => 5,
            'arrival' => '2019/05/05',
            'departure' => '2019/05/09',
            'guest_id' => 999,
        ];
        $response = $this->json('POST', '/api/booking/' . $booking->id, $post);
        $response
            ->assertStatus(422)
            ->assertJsonFragment([
                'guest_id' => ['The selected guest id is invalid.'],
            ]);
    }

    /** @test */
    public function updating_valid_booking_should_respond_with_booking_details()
    {
        $booking = Booking::create([
            'room_id' => 5,
            'arrival' => '2019/05/05',
            'departure' => '2019/05/09',
            'guest_id' => 1,
        ]);

        $post = [
            'room_id' => 5,
            'arrival' => '2019/05/05',
            'departure' => '2019/06/01',
            'guest_id' => 1,
        ];
        $response = $this->json('POST', '/api/booking/' . $booking->id, $post);
        $response
            ->assertStatus(200)
            ->assertJsonFragment([
                'departure' => '2019/06/01',
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
    public function updating_room_id_should_cascade_to_guest_table()
    {
        $booking = Booking::create([
            'room_id' => 5,
            'arrival' => '2019/05/05',
            'departure' => '2019/05/09',
            'guest_id' => 1,
        ]);
        $post = [
            'room_id' => 9,
            'arrival' => '2019/05/05',
            'departure' => '2019/05/09',
            'guest_id' => 1,
        ];
        $response1 = $this->json('POST', '/api/booking/' . $booking->id, $post);
        $response1
            ->assertStatus(200)
            ->assertJsonFragment([
                'room_id' => 9,
            ]);
        $response2 = $this->json('GET', '/api/guest/' . $post['guest_id']);
        $response2->assertJson([
            'room_id' => 9,
        ]);

    }

    /** @test */
    public function updating_guest_id_should_update_new_guest_with_room_AND_old_guest_to_null()
    {
        $booking = Booking::create([
            'room_id' => 5,
            'arrival' => '2019/05/05',
            'departure' => '2019/05/09',
            'guest_id' => 1,
        ]);
        $guest = Guest::findOrFail(1);
        $guest->update([
            'room_id' => 5,
        ]);
        $guest->push();

        $post = [
            'room_id' => 5,
            'arrival' => '2019/05/05',
            'departure' => '2019/05/09',
            'guest_id' => 2,
        ];
        $this->json('POST', '/api/booking/' . $booking->id, $post);
        $response1 = $this->json('GET', '/api/guest/2');
        $response1->assertJson([
            'id' => 2,
            'room_id' => 5,
        ]);
        $response2 = $this->json('GET', '/api/guest/1');
        $response2->assertJson([
            'id' => 1,
            'room_id' => null,
        ]);

    }
}

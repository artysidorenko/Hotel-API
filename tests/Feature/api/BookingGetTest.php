<?php

namespace Tests\Feature\api;

use App\Booking;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class BookingGetTest extends TestCase
{

    use DatabaseTransactions;

    /** @test */
    public function requesting_an_invalid_booking_triggers_model_not_found_exception()
    {
        $this->withoutExceptionHandling();

        $this->expectException(ModelNotFoundException::class);
        $this->json('GET', '/api/booking/999');
    }

    /** @test */
    public function requesting_an_invalid_booking_triggers_valid_exception_message()
    {
        $this->withoutExceptionHandling();

        try {
            $this->json('GET', '/api/booking/999');
        } catch (ModelNotFoundException $exception) {
            $this->assertEquals('No query results for model [App\Booking] 999', $exception->getMessage());
            return;
        }

        $this->fail('ModelNotFoundException should be triggered.');
    }

    /** @test */
    public function requesting_an_invalid_booking_returns_no_query_results_error()
    {
        $response = $this->json('GET', '/api/booking/999');
        $response->assertStatus(404);
        $response->assertHeader('Content-Type', 'application/json');
        $response->assertJson([
            'message' => 'No query results for model [App\Booking] 999',
        ]);
    }

    /** @test */
    public function invalid_format_booking_uri_triggers_fallback_route()
    {
        $response = $this->json('GET', '/api/booking/invalid-Booking-id');
        $response->assertStatus(404);
        $response->assertHeader('Content-Type', 'application/json');
        $response->assertJson([
            'error' => 'Not Found.',
        ]);
    }

    /** @test */
    public function valid_request_to_booking_id_endpoint_generates_valid_json_response()
    {
        $booking = Booking::create([
            'room_id' => 5,
            'arrival' => '2019/05/05',
            'departure' => '2019/05/09',
            'guest_id' => 1,
        ]);
        $response = $this->json('GET', '/api/booking/' . $booking->id);
        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/json');
        $response->assertJsonCount(5);
        $response->assertJson([
            'id' => $booking->id,
        ])
            ->assertJsonStructure([
                '*' => 'id',
                '*' => 'room_id',
                '*' => 'arrival',
                '*' => 'departure',
                '*' => 'guest_id',
            ]);
    }

    /** @test */
    public function valid_request_to_bookings_endpoint_generates_valid_json_list_response()
    {
        $booking1 = Booking::create([
            'room_id' => 5,
            'arrival' => '2019/05/05',
            'departure' => '2019/05/09',
            'guest_id' => 1,
        ]);
        $booking2 = Booking::create([
            'room_id' => 5,
            'arrival' => '2019/05/05',
            'departure' => '2019/05/09',
            'guest_id' => 2,
        ]);

        $response = $this->json('GET', '/api/bookings');
        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/json');
        $response->assertJsonCount(5, '0.*');
        $response->assertJsonFragment([
            'id' => $booking1->id,
        ]);
    }
}

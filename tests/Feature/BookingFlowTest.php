<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Package;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookingFlowTest extends TestCase
{
    use RefreshDatabase;

    private function makePackage(array $overrides = []): Package
    {
        return Package::create(array_merge([
            'title' => 'Desert Sunset Ride',
            'category' => 'adventure',
            'description' => 'A ride through the dunes.',
            'duration' => '2 hours',
            'price' => 500,
            'image' => 'https://example.com/photo.jpg',
            'status' => 'active',
        ], $overrides));
    }

    private function validPayload(Package $package, array $overrides = []): array
    {
        return array_merge([
            'customer_name' => 'Jane Traveler',
            'email' => 'jane@example.com',
            'phone' => '+212600000000',
            'package_id' => $package->id,
            'visit_date' => now()->addDays(7)->toDateString(),
            'guests' => 3,
            'contact_method' => 'whatsapp',
            'hotel' => 'Riad Atlas',
            'notes' => 'Vegetarian please.',
            'consent' => '1',
            'request_id' => 'test-request-0001',
        ], $overrides);
    }

    public function test_booking_saves_all_form_fields(): void
    {
        $package = $this->makePackage();

        $response = $this->postJson('/bookings', $this->validPayload($package));

        $response->assertOk()->assertJson(['success' => true]);
        $this->assertStringStartsWith('AGF-', $response->json('reference'));

        $this->assertDatabaseHas('bookings', [
            'customer_name' => 'Jane Traveler',
            'email' => 'jane@example.com',
            'phone' => '+212600000000',
            'package_id' => $package->id,
            'package_name' => 'Desert Sunset Ride',
            'unit_price' => 500,
            'guests' => 3,
            'contact_method' => 'whatsapp',
            'hotel' => 'Riad Atlas',
            'notes' => 'Vegetarian please.',
            'consent' => 1,
            'status' => 'pending',
            'request_id' => 'test-request-0001',
        ]);
    }

    public function test_server_derives_package_name_and_price_not_client(): void
    {
        $package = $this->makePackage(['price' => 750]);

        $this->postJson('/bookings', $this->validPayload($package, [
            'package_name' => 'Hacked Title',
        ]));

        $this->assertDatabaseHas('bookings', [
            'package_name' => 'Desert Sunset Ride',
            'unit_price' => 750,
        ]);
    }

    public function test_duplicate_request_id_is_ignored(): void
    {
        $package = $this->makePackage();
        $payload = $this->validPayload($package);

        $first = $this->postJson('/bookings', $payload);
        $second = $this->postJson('/bookings', $payload);

        $first->assertOk();
        $second->assertOk()->assertJson(['success' => true]);
        $this->assertSame($first->json('reference'), $second->json('reference'));
        $this->assertSame(1, Booking::count());
    }

    public function test_honeypot_is_rejected(): void
    {
        $package = $this->makePackage();

        $response = $this->postJson('/bookings', $this->validPayload($package, [
            'company_website' => 'http://spam.example',
        ]));

        $response->assertStatus(422);
        $this->assertSame(0, Booking::count());
    }

    public function test_missing_consent_is_rejected(): void
    {
        $package = $this->makePackage();

        $response = $this->postJson('/bookings', $this->validPayload($package, [
            'consent' => null,
        ]));

        $response->assertStatus(422);
        $this->assertSame(0, Booking::count());
    }

    public function test_past_visit_date_is_rejected(): void
    {
        $package = $this->makePackage();

        $response = $this->postJson('/bookings', $this->validPayload($package, [
            'visit_date' => now()->subDay()->toDateString(),
        ]));

        $response->assertStatus(422);
    }

    public function test_soft_deleted_package_cannot_be_booked(): void
    {
        $package = $this->makePackage();
        $package->delete();

        $response = $this->postJson('/bookings', $this->validPayload($package));

        $response->assertStatus(422);
        $this->assertSame(0, Booking::count());
    }

    public function test_booking_rate_limited(): void
    {
        $package = $this->makePackage();

        foreach (range(1, 10) as $i) {
            $this->postJson('/bookings', $this->validPayload($package, [
                'request_id' => "req-$i",
                'email' => "user$i@example.com",
            ]));
        }

        $response = $this->postJson('/bookings', $this->validPayload($package, [
            'request_id' => 'req-11',
            'email' => 'user11@example.com',
        ]));

        $response->assertStatus(429);
    }
}

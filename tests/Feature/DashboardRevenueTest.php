<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Package;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardRevenueTest extends TestCase
{
    use RefreshDatabase;

    private function owner(): User
    {
        return User::factory()->create(['role' => 'owner']);
    }

    private function makeBooking(array $overrides = []): Booking
    {
        $package = Package::create([
            'title' => $overrides['package_name'] ?? 'Desert Camp',
            'category' => 'relax',
            'price' => 1000,
            'status' => 'active',
        ]);

        return Booking::create(array_merge([
            'customer_name' => 'Someone',
            'email' => 'someone@example.com',
            'phone' => '+212600000000',
            'package_id' => $package->id,
            'package_name' => $package->title,
            'unit_price' => 1000,
            'visit_date' => now()->addWeek()->toDateString(),
            'guests' => 2,
            'status' => 'confirmed',
        ], $overrides));
    }

    public function test_dashboard_revenue_uses_unit_price_snapshot(): void
    {
        $booking = $this->makeBooking(['unit_price' => 1000, 'guests' => 2]);

        $booking->package->update(['price' => 99999]);

        $response = $this->actingAs($this->owner())->get('/dashboard');

        $response->assertOk();
        $this->assertSame(2000, (int) $response->viewData('totalRevenue'));
    }

    public function test_revenue_survives_soft_deleted_package(): void
    {
        $booking = $this->makeBooking(['unit_price' => 1000, 'guests' => 3]);
        $booking->package->delete();

        $response = $this->actingAs($this->owner())->get('/dashboard');

        $response->assertOk();
        $this->assertSame(3000, (int) $response->viewData('totalRevenue'));
    }

    public function test_pending_bookings_do_not_count_as_revenue(): void
    {
        $this->makeBooking(['status' => 'pending', 'unit_price' => 1000, 'guests' => 2]);

        $response = $this->actingAs($this->owner())->get('/dashboard');

        $this->assertSame(0, (int) $response->viewData('totalRevenue'));
        $this->assertSame(1, (int) $response->viewData('totalOrders'));
        $this->assertSame(1, (int) $response->viewData('pendingOrders'));
    }

    public function test_best_sellers_revenue_is_real_not_scaled(): void
    {
        $this->makeBooking(['unit_price' => 500, 'guests' => 2]);

        $response = $this->actingAs($this->owner())->get('/dashboard');

        $bestSellers = $response->viewData('bestSellers');
        $this->assertCount(1, $bestSellers);
        $this->assertSame(1000, (int) $bestSellers[0]->total_revenue);
    }

    public function test_bookings_without_package_name_group_as_unknown(): void
    {
        $this->makeBooking(['package_name' => null, 'unit_price' => 500, 'guests' => 1]);

        $response = $this->actingAs($this->owner())->get('/dashboard');

        $revenueByPackage = $response->viewData('revenueByPackage');
        $this->assertSame('Unknown', $revenueByPackage[0]->package_name);
        $this->assertSame(500, (int) $revenueByPackage[0]->revenue);
    }

    public function test_csv_report_downloads_with_revenue(): void
    {
        $this->makeBooking(['unit_price' => 1000, 'guests' => 2]);

        $response = $this->actingAs($this->owner())->get('/dashboard/report');

        $response->assertOk();
        $this->assertStringContainsString('text/csv', (string) $response->headers->get('Content-Type'));
        $this->assertStringContainsString('monthly-report-', (string) $response->headers->get('Content-Disposition'));
        $this->assertStringContainsString('2000', $response->getContent());
    }
}

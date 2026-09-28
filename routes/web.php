<?php

use App\Http\Controllers\BookingController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\PackageController;
use App\Http\Controllers\ProfileController;
use App\Models\Booking;
use App\Models\Package;
use Illuminate\Support\Facades\Route;

$bookingRevenue = fn ($booking) => ($booking->unit_price ?? $booking->package?->price ?? 0) * $booking->guests;

Route::get('/', function () {
    $packages = Package::where('status', 'active')->get();
    $bookingEndpoint = route('bookings.store');
    $contactEndpoint = route('contact.store');

    return view('landing.index', compact('packages', 'bookingEndpoint', 'contactEndpoint'));
});

Route::middleware(['auth', 'verified', 'owner'])->group(function () use ($bookingRevenue) {
    Route::get('/dashboard/report', function () use ($bookingRevenue) {
        $monthlyRevenue = Booking::whereIn('status', ['confirmed', 'completed'])
            ->with('package')
            ->get()
            ->groupBy(fn ($b) => $b->created_at->format('Y-m'))
            ->map(fn ($group) => [
                'month' => $group->first()->created_at->format('F Y'),
                'orders' => $group->count(),
                'revenue' => $group->sum($bookingRevenue),
            ])
            ->sortKeys()
            ->values();

        $filename = 'monthly-report-'.date('Y-m-d').'.csv';
        $handle = fopen('php://temp', 'r+');
        fputcsv($handle, ['Month', 'Orders', 'Revenue (MAD)']);
        foreach ($monthlyRevenue as $row) {
            fputcsv($handle, [$row['month'], $row['orders'], $row['revenue']]);
        }
        rewind($handle);
        $csv = stream_get_contents($handle);
        fclose($handle);

        return response($csv, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="'.$filename.'"',
        ]);
    })->name('dashboard.report');

    Route::get('/dashboard', function () use ($bookingRevenue) {
        $totalOrders = Booking::count();
        $pendingOrders = Booking::where('status', 'pending')->count();
        $confirmedOrders = Booking::where('status', 'confirmed')->count();
        $completedOrders = Booking::where('status', 'completed')->count();
        $totalRevenue = Booking::whereIn('status', ['confirmed', 'completed'])
            ->with('package')
            ->get()
            ->sum($bookingRevenue);
        $recentBookings = Booking::latest()->take(5)->get();

        $monthlyRevenue = Booking::whereIn('status', ['confirmed', 'completed'])
            ->with('package')
            ->get()
            ->groupBy(fn ($b) => $b->created_at->format('Y-m'))
            ->map(fn ($group) => (object) [
                'month' => $group->first()->created_at->format('M'),
                'revenue' => $group->sum($bookingRevenue),
            ])
            ->sortKeys()
            ->values();

        $revenueByPackage = Booking::whereIn('status', ['confirmed', 'completed'])
            ->with('package')
            ->get()
            ->groupBy(fn ($b) => $b->package_name ?: 'Unknown')
            ->map(fn ($group) => (object) [
                'package_name' => $group->first()->package_name ?: 'Unknown',
                'revenue' => $group->sum($bookingRevenue),
            ])
            ->sortByDesc('revenue')
            ->values();

        $bestSellers = Booking::whereIn('status', ['confirmed', 'completed'])
            ->with('package')
            ->get()
            ->groupBy(fn ($b) => $b->package_name ?: 'Unknown')
            ->map(fn ($group) => (object) [
                'package_name' => $group->first()->package_name ?: 'Unknown',
                'total_bookings' => $group->count(),
                'total_revenue' => $group->sum($bookingRevenue),
            ])
            ->sortByDesc('total_bookings')
            ->take(5)
            ->values();

        return view('dashboard', compact('totalOrders', 'pendingOrders', 'confirmedOrders', 'completedOrders', 'totalRevenue', 'recentBookings', 'monthlyRevenue', 'revenueByPackage', 'bestSellers'));
    })->name('dashboard');

    Route::middleware('auth')->group(function () {
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

        Route::resource('admin/packages', PackageController::class)->except(['show'])->names([
            'index' => 'admin.packages',
            'create' => 'admin.packages.create',
            'store' => 'admin.packages.store',
            'edit' => 'admin.packages.edit',
            'update' => 'admin.packages.update',
            'destroy' => 'admin.packages.destroy',
        ]);

        Route::get('/admin/orders', [BookingController::class, 'index'])->name('admin.orders');
        Route::get('/admin/orders/{booking}', [BookingController::class, 'show'])->name('admin.orders.show');
        Route::post('/admin/orders/{booking}/confirm', [BookingController::class, 'confirm'])->name('admin.orders.confirm');
        Route::post('/admin/orders/{booking}/complete', [BookingController::class, 'complete'])->name('admin.orders.complete');
        Route::delete('/admin/orders/{booking}', [BookingController::class, 'destroy'])->name('admin.orders.destroy');

        Route::get('/admin/messages', [MessageController::class, 'index'])->name('admin.messages');
        Route::post('/admin/messages/{message}/read', [MessageController::class, 'markRead'])->name('admin.messages.read');
        Route::delete('/admin/messages/{message}', [MessageController::class, 'destroy'])->name('admin.messages.destroy');
    });
});

Route::post('/bookings', [BookingController::class, 'store'])
    ->middleware('throttle:10,1')
    ->name('bookings.store');

Route::post('/contacts', [ContactController::class, 'store'])
    ->middleware('throttle:10,1')
    ->name('contact.store');

require __DIR__.'/auth.php';

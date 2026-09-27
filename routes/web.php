<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $packages = \App\Models\Package::where('status', 'active')->get();
    $bookingEndpoint = route('bookings.store');
    $contactEndpoint = route('bookings.store');
    return view('landing.index', compact('packages', 'bookingEndpoint', 'contactEndpoint'));
});

Route::get('/dashboard/report', function () {
    $monthlyRevenue = \App\Models\Booking::whereIn('status', ['confirmed', 'completed'])
        ->with('package')
        ->get()
        ->groupBy(fn($b) => $b->created_at->format('M'))
        ->map(fn($group) => [
            'month' => $group->first()->created_at->format('F Y'),
            'orders' => $group->count(),
            'revenue' => $group->sum(fn($b) => ($b->package?->price ?? 0) * $b->guests),
        ])
        ->values();

    $filename = 'monthly-report-' . date('Y-m-d') . '.csv';
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
        'Content-Disposition' => 'attachment; filename="' . $filename . '"',
    ]);
})->middleware(['auth', 'verified'])->name('dashboard.report');

Route::get('/dashboard', function () {
    $totalOrders = \App\Models\Booking::count();
    $pendingOrders = \App\Models\Booking::where('status', 'pending')->count();
    $confirmedOrders = \App\Models\Booking::where('status', 'confirmed')->count();
    $completedOrders = \App\Models\Booking::where('status', 'completed')->count();
    $totalRevenue = \App\Models\Booking::whereIn('status', ['confirmed', 'completed'])
        ->with('package')
        ->get()
        ->sum(fn($b) => ($b->package?->price ?? 0) * $b->guests);
    $recentBookings = \App\Models\Booking::latest()->take(5)->get();

    $monthlyRevenue = \App\Models\Booking::whereIn('status', ['confirmed', 'completed'])
        ->with('package')
        ->get()
        ->groupBy(fn($b) => $b->created_at->format('M'))
        ->map(fn($group) => (object)[
            'month' => $group->first()->created_at->format('M'),
            'revenue' => $group->sum(fn($b) => ($b->package?->price ?? 0) * $b->guests),
        ])
        ->values();

    $revenueByPackage = \App\Models\Booking::whereIn('status', ['confirmed', 'completed'])
        ->with('package')
        ->get()
        ->groupBy('package_name')
        ->map(fn($group) => (object)[
            'package_name' => $group->first()->package_name,
            'revenue' => $group->sum(fn($b) => ($b->package?->price ?? 0) * $b->guests),
        ])
        ->values();

    $bestSellers = \App\Models\Booking::whereIn('status', ['confirmed', 'completed'])
        ->selectRaw('package_name, COUNT(*) as total_bookings, SUM(guests) as total_revenue')
        ->groupBy('package_name')
        ->orderBy('total_bookings', 'desc')
        ->take(5)
        ->get()
        ->map(fn($b) => (object)[
            'package_name' => $b->package_name,
            'total_bookings' => $b->total_bookings,
            'total_revenue' => $b->total_revenue * 500,
        ]);

    return view('dashboard', compact('totalOrders', 'pendingOrders', 'confirmedOrders', 'completedOrders', 'totalRevenue', 'recentBookings', 'monthlyRevenue', 'revenueByPackage', 'bestSellers'));
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('admin/packages', \App\Http\Controllers\PackageController::class)->names([
        'index' => 'admin.packages',
        'create' => 'admin.packages.create',
        'store' => 'admin.packages.store',
        'edit' => 'admin.packages.edit',
        'update' => 'admin.packages.update',
        'destroy' => 'admin.packages.destroy',
    ]);

    Route::get('/admin/orders', [\App\Http\Controllers\BookingController::class, 'index'])->name('admin.orders');
    Route::get('/admin/orders/{booking}', [\App\Http\Controllers\BookingController::class, 'show'])->name('admin.orders.show');
    Route::post('/admin/orders/{booking}/confirm', [\App\Http\Controllers\BookingController::class, 'confirm'])->name('admin.orders.confirm');
    Route::post('/admin/orders/{booking}/complete', [\App\Http\Controllers\BookingController::class, 'complete'])->name('admin.orders.complete');
    Route::delete('/admin/orders/{booking}', [\App\Http\Controllers\BookingController::class, 'destroy'])->name('admin.orders.destroy');

});

Route::post('/bookings', [\App\Http\Controllers\BookingController::class, 'store'])->name('bookings.store');

require __DIR__.'/auth.php';

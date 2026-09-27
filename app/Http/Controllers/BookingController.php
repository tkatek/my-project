<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function index()
    {
        $bookings = Booking::latest()->paginate(10);
        return view('admin.orders.index', compact('bookings'));
    }

    public function show(Booking $booking)
    {
        return view('admin.orders.show', compact('booking'));
    }

    public function confirm(Booking $booking)
    {
        $booking->update(['status' => 'confirmed']);

        return redirect()->route('admin.orders')->with('success', 'Booking confirmed.');
    }

    public function complete(Booking $booking)
    {
        $booking->update(['status' => 'completed']);

        return redirect()->route('admin.orders')->with('success', 'Booking marked as completed.');
    }

    public function destroy(Booking $booking)
    {
        $booking->delete();

        return redirect()->route('admin.orders')->with('success', 'Booking deleted.');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:255',
            'package_id' => 'nullable|exists:packages,id',
            'package_name' => 'nullable|string|max:255',
            'visit_date' => 'nullable|date',
            'guests' => 'required|integer|min:1',
            'notes' => 'nullable|string',
        ]);

        $validated['status'] = 'pending';

        Booking::create($validated);

        return response()->json(['success' => true, 'message' => 'Booking submitted successfully.']);
    }
}

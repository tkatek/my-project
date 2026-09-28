<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Package;
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
            'customer_name' => 'required|string|min:2|max:100',
            'email' => 'required|email|max:160',
            'phone' => 'required|string|max:25',
            'package_id' => 'required|exists:packages,id',
            'visit_date' => 'required|date|after_or_equal:today',
            'guests' => 'required|integer|min:1|max:12',
            'contact_method' => 'required|in:whatsapp,email,phone',
            'hotel' => 'nullable|string|max:180',
            'notes' => 'nullable|string|max:1500',
            'consent' => 'accepted',
            'company_website' => 'nullable|max:0',
        ]);

        $package = Package::find($validated['package_id']);

        if (! $package) {
            return response()->json([
                'success' => false,
                'message' => 'That experience is no longer available. Please choose another.',
            ], 422);
        }

        $requestId = $request->input('request_id') ?: $request->header('Idempotency-Key');

        if ($requestId && Booking::where('request_id', $requestId)->exists()) {
            $existing = Booking::where('request_id', $requestId)->first();

            return response()->json([
                'success' => true,
                'reference' => $this->reference($existing),
            ]);
        }

        $booking = Booking::create([
            'customer_name' => $validated['customer_name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'package_id' => $package->id,
            'package_name' => $package->title,
            'unit_price' => $package->price,
            'visit_date' => $validated['visit_date'],
            'guests' => $validated['guests'],
            'contact_method' => $validated['contact_method'],
            'hotel' => $validated['hotel'] ?? null,
            'notes' => $validated['notes'] ?? null,
            'consent' => true,
            'request_id' => $requestId,
            'status' => 'pending',
        ]);

        return response()->json([
            'success' => true,
            'reference' => $this->reference($booking),
        ]);
    }

    private function reference(Booking $booking): string
    {
        return 'AGF-'.str_pad($booking->id, 4, '0', STR_PAD_LEFT);
    }
}

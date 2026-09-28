<x-sidebar-layout>
    <x-slot name="header">Order Details</x-slot>

    <div style="max-width:700px;">
        <div style="background:#fff;border-radius:12px;padding:32px;box-shadow:0 1px 3px rgba(0,0,0,0.06);">
            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:24px;">
                <h2 style="margin:0;font-size:20px;font-weight:600;color:#111827;">Order #{{ $booking->id }}</h2>
                <span style="padding:6px 16px;font-size:12px;font-weight:600;border-radius:9999px;{{ $booking->status === 'confirmed' ? 'background:#dbeafe;color:#1e40af;' : ($booking->status === 'completed' ? 'background:#d1fae5;color:#065f46;' : 'background:#fef3c7;color:#92400e;') }}">{{ ucfirst($booking->status) }}</span>
            </div>

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:24px;margin-bottom:24px;">
                <div>
                    <p style="margin:0 0 4px;font-size:12px;color:#9ca3af;text-transform:uppercase;">Customer Name</p>
                    <p style="margin:0;font-size:16px;font-weight:500;color:#111827;">{{ $booking->customer_name }}</p>
                </div>
                <div>
                    <p style="margin:0 0 4px;font-size:12px;color:#9ca3af;text-transform:uppercase;">Email</p>
                    <p style="margin:0;font-size:16px;font-weight:500;color:#111827;">{{ $booking->email }}</p>
                </div>
                <div>
                    <p style="margin:0 0 4px;font-size:12px;color:#9ca3af;text-transform:uppercase;">Phone</p>
                    <p style="margin:0;font-size:16px;font-weight:500;color:#111827;">{{ $booking->phone ?? 'N/A' }}</p>
                </div>
                <div>
                    <p style="margin:0 0 4px;font-size:12px;color:#9ca3af;text-transform:uppercase;">Guests</p>
                    <p style="margin:0;font-size:16px;font-weight:500;color:#111827;">{{ $booking->guests }}</p>
                </div>
                <div>
                    <p style="margin:0 0 4px;font-size:12px;color:#9ca3af;text-transform:uppercase;">Preferred Date</p>
                    <p style="margin:0;font-size:16px;font-weight:500;color:#111827;">{{ $booking->visit_date ? date('d/m/Y', strtotime($booking->visit_date)) : 'N/A' }}</p>
                </div>
                <div>
                    <p style="margin:0 0 4px;font-size:12px;color:#9ca3af;text-transform:uppercase;">Package</p>
                    <p style="margin:0;font-size:16px;font-weight:500;color:#111827;">{{ $booking->package_name ?? 'N/A' }}</p>
                </div>
                <div>
                    <p style="margin:0 0 4px;font-size:12px;color:#9ca3af;text-transform:uppercase;">Contact by</p>
                    <p style="margin:0;font-size:16px;font-weight:500;color:#111827;text-transform:capitalize;">{{ $booking->contact_method ?? 'N/A' }}</p>
                </div>
                <div>
                    <p style="margin:0 0 4px;font-size:12px;color:#9ca3af;text-transform:uppercase;">Hotel / Riad</p>
                    <p style="margin:0;font-size:16px;font-weight:500;color:#111827;">{{ $booking->hotel ?? 'Not provided' }}</p>
                </div>
                <div>
                    <p style="margin:0 0 4px;font-size:12px;color:#9ca3af;text-transform:uppercase;">Consent</p>
                    <p style="margin:0;font-size:16px;font-weight:500;color:{{ $booking->consent ? '#065f46' : '#b91c1c' }};">{{ $booking->consent ? 'Given' : 'Not given' }}</p>
                </div>
                <div>
                    <p style="margin:0 0 4px;font-size:12px;color:#9ca3af;text-transform:uppercase;">Price</p>
                    <p style="margin:0;font-size:16px;font-weight:600;color:#111827;">MAD {{ number_format($booking->unit_price ?? $booking->package?->price ?? 0) }}</p>
                </div>
                <div>
                    <p style="margin:0 0 4px;font-size:12px;color:#9ca3af;text-transform:uppercase;">Total (Guests)</p>
                    <p style="margin:0;font-size:16px;font-weight:600;color:#111827;">MAD {{ number_format(($booking->unit_price ?? $booking->package?->price ?? 0) * $booking->guests) }}</p>
                </div>
            </div>

            @if($booking->notes)
            <div style="margin-bottom:24px;">
                <p style="margin:0 0 8px;font-size:12px;color:#9ca3af;text-transform:uppercase;">Notes</p>
                <p style="margin:0;font-size:14px;color:#4b5563;background:#f9fafb;padding:16px;border-radius:8px;">{{ $booking->notes }}</p>
            </div>
            @endif

            <div style="display:flex;gap:12px;flex-wrap:wrap;">
                <a href="{{ route('admin.orders') }}" style="padding:10px 20px;background:#f3f4f6;color:#374151;border-radius:8px;font-size:14px;font-weight:500;text-decoration:none;">Back to Orders</a>
                @if($booking->status === 'pending')
                <form method="POST" action="{{ route('admin.orders.confirm', $booking) }}">
                    @csrf
                    <button type="submit" style="padding:10px 20px;background:#16a34a;color:#fff;border:none;border-radius:8px;font-size:14px;font-weight:600;cursor:pointer;">Confirm</button>
                </form>
                @endif
                @if($booking->status === 'confirmed')
                <form method="POST" action="{{ route('admin.orders.complete', $booking) }}">
                    @csrf
                    <button type="submit" style="padding:10px 20px;background:#2563eb;color:#fff;border:none;border-radius:8px;font-size:14px;font-weight:600;cursor:pointer;">Mark Completed</button>
                </form>
                @endif
            </div>
        </div>
    </div>
</x-sidebar-layout>

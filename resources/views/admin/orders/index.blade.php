<x-sidebar-layout>
    <x-slot name="header">Orders</x-slot>

    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:24px;">
        <p style="margin:0;font-size:14px;color:#6b7280;">{{ $bookings->count() }} orders total</p>
        <select style="padding:8px 16px;border:1px solid #e5e7eb;border-radius:8px;font-size:14px;background:#fff;">
            <option>All Status</option>
            <option>Pending</option>
            <option>Confirmed</option>
            <option>Completed</option>
        </select>
    </div>

    <div class="orders-table-container">
        <table class="orders-table">
            <thead>
                <tr>
                    <th>Customer</th>
                    <th>Package</th>
                    <th>Visit Date</th>
                    <th>Guests</th>
                    <th>Status</th>
                    <th class="actions-col">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($bookings as $booking)
                <tr>
                    <td>
                        <p style="margin:0;font-weight:500;color:#111827;">{{ $booking->customer_name }}</p>
                        <p style="margin:2px 0 0;font-size:12px;color:#9ca3af;">{{ $booking->email }}</p>
                    </td>
                    <td style="color:#4b5563;">{{ $booking->package_name ?? 'N/A' }}</td>
                    <td style="color:#4b5563;">{{ $booking->visit_date ? date('d/m/Y', strtotime($booking->visit_date)) : 'N/A' }}</td>
                    <td style="color:#4b5563;">{{ $booking->guests }}</td>
                    <td>
                        <span class="status-badge {{ $booking->status }}">{{ ucfirst($booking->status) }}</span>
                    </td>
                    <td class="actions-col">
                        <div class="action-btns">
                            <a href="{{ route('admin.orders.show', $booking) }}" class="btn-view">View</a>
                            @if($booking->status === 'pending')
                            <form method="POST" action="{{ route('admin.orders.confirm', $booking) }}">
                                @csrf
                                <button type="submit" class="btn-confirm">Confirm</button>
                            </form>
                            @endif
                            @if($booking->status === 'confirmed')
                            <form method="POST" action="{{ route('admin.orders.complete', $booking) }}">
                                @csrf
                                <button type="submit" class="btn-complete">Complete</button>
                            </form>
                            @endif
                            <form method="POST" action="{{ route('admin.orders.destroy', $booking) }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-delete" onclick="return confirm('Delete this order?')">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="empty-cell">No orders yet. Orders from the landing page will appear here.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top:24px;">
        {{ $bookings->links() }}
    </div>

    <style>
        .orders-table-container { background:#fff; border-radius:12px; box-shadow:0 1px 3px rgba(0,0,0,0.06); overflow:hidden; }
        .orders-table { width:100%; border-collapse:collapse; }
        .orders-table th { padding:12px 16px; text-align:left; font-size:12px; font-weight:600; color:#6b7280; text-transform:uppercase; border-bottom:1px solid #e5e7eb; }
        .orders-table td { padding:16px; font-size:14px; border-bottom:1px solid #f3f4f6; vertical-align:top; }
        .orders-table tr:hover td { background:#f9fafb; }
        .status-badge { padding:4px 12px; font-size:12px; font-weight:600; border-radius:9999px; display:inline-block; }
        .status-badge.pending { background:#fef3c7; color:#92400e; }
        .status-badge.confirmed { background:#dbeafe; color:#1e40af; }
        .status-badge.completed { background:#d1fae5; color:#065f46; }
        .action-btns { display:flex; gap:6px; flex-wrap:wrap; align-items:center; }
        .btn-view { padding:6px 12px; font-size:12px; font-weight:500; color:#3b82f6; background:#eff6ff; border-radius:6px; text-decoration:none; }
        .btn-confirm { padding:6px 12px; font-size:12px; font-weight:500; color:#16a34a; background:#f0fdf4; border:none; border-radius:6px; cursor:pointer; }
        .btn-complete { padding:6px 12px; font-size:12px; font-weight:500; color:#1e40af; background:#eff6ff; border:none; border-radius:6px; cursor:pointer; }
        .btn-delete { padding:6px 12px; font-size:12px; font-weight:500; color:#dc2626; background:#fef2f2; border:none; border-radius:6px; cursor:pointer; }
        .empty-cell { text-align:center; color:#9ca3af; padding:48px; }

        @media (max-width:768px) {
            .orders-table-container { background:transparent; box-shadow:none; }
            .orders-table thead { display:none; }
            .orders-table tbody { display:flex; flex-direction:column; gap:12px; }
            .orders-table tr { display:flex; flex-direction:column; background:#fff; border-radius:12px; padding:16px; box-shadow:0 1px 3px rgba(0,0,0,0.06); border-bottom:none; }
            .orders-table td { display:flex; justify-content:space-between; align-items:center; padding:8px 0; border-bottom:1px solid #f3f4f6; }
            .orders-table td::before { content:attr(data-label); font-weight:600; color:#6b7280; font-size:12px; text-transform:uppercase; }
            .actions-col { flex-direction:column !important; align-items:stretch !important; }
            .action-btns { flex-direction:column; }
            .action-btns a, .action-btns button { width:100%; text-align:center; }
        }
        @media (max-width:480px) {
            .orders-table td { flex-direction:column; align-items:flex-start; gap:4px; }
            .orders-table td::before { margin-bottom:4px; }
        }
    </style>
</x-sidebar-layout>

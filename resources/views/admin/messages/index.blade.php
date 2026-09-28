<x-sidebar-layout>
    <x-slot name="header">Messages</x-slot>

    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:20px;">
        <p style="margin:0;color:#6b7280;font-size:14px;">{{ $messages->total() }} message{{ $messages->total() === 1 ? '' : 's' }} from the contact form.</p>
    </div>

    @if (session('success'))
        <div style="background:#d1fae5;color:#065f46;padding:12px 16px;border-radius:8px;margin-bottom:16px;font-size:14px;">{{ session('success') }}</div>
    @endif

    @if ($messages->isEmpty())
        <div style="background:#fff;border-radius:12px;padding:48px;text-align:center;box-shadow:0 1px 3px rgba(0,0,0,0.06);">
            <p style="margin:0;color:#9ca3af;font-size:15px;">No messages yet. Inquiries from the landing page contact form will appear here.</p>
        </div>
    @else
        <div style="display:flex;flex-direction:column;gap:12px;">
            @foreach ($messages as $message)
                <div style="background:#fff;border-radius:12px;padding:20px 24px;box-shadow:0 1px 3px rgba(0,0,0,0.06);{{ $message->isUnread() ? 'border-left:4px solid #f97316;' : '' }}">
                    <div style="display:flex;justify-content:space-between;align-items:flex-start;gap:16px;flex-wrap:wrap;">
                        <div style="min-width:220px;">
                            <p style="margin:0 0 4px;font-size:16px;font-weight:{{ $message->isUnread() ? '700' : '600' }};color:#111827;">
                                {{ $message->name }}
                                @if ($message->isUnread())
                                    <span style="background:#fff3e6;color:#c2410c;font-size:11px;font-weight:700;padding:2px 8px;border-radius:9999px;margin-left:6px;">NEW</span>
                                @endif
                            </p>
                            <p style="margin:0;font-size:13px;color:#6b7280;">
                                <a href="mailto:{{ $message->email }}" style="color:#2563eb;text-decoration:none;">{{ $message->email }}</a>
                                @if ($message->phone)
                                    &nbsp;·&nbsp; <a href="tel:{{ $message->phone }}" style="color:#2563eb;text-decoration:none;">{{ $message->phone }}</a>
                                @endif
                            </p>
                        </div>
                        <div style="text-align:right;">
                            <p style="margin:0 0 4px;font-size:12px;color:#9ca3af;">{{ $message->created_at->format('d M Y, H:i') }}</p>
                            <span style="display:inline-block;padding:4px 12px;border-radius:9999px;font-size:12px;font-weight:600;background:#f3f4f6;color:#374151;text-transform:capitalize;">{{ $message->subject }}</span>
                        </div>
                    </div>

                    <p style="margin:16px 0 0;font-size:14px;line-height:1.6;color:#4b5563;background:#f9fafb;padding:16px;border-radius:8px;white-space:pre-wrap;">{{ $message->message }}</p>

                    <div style="display:flex;gap:10px;margin-top:16px;flex-wrap:wrap;">
                        <a href="mailto:{{ $message->email }}?subject=Re: your inquiry" style="padding:8px 16px;background:#f97316;color:#fff;border-radius:8px;font-size:13px;font-weight:600;text-decoration:none;">Reply by email</a>
                        @if ($message->isUnread())
                            <form method="POST" action="{{ route('admin.messages.read', $message) }}">
                                @csrf
                                <button type="submit" style="padding:8px 16px;background:#f3f4f6;color:#374151;border:none;border-radius:8px;font-size:13px;font-weight:500;cursor:pointer;">Mark as read</button>
                            </form>
                        @endif
                        <form method="POST" action="{{ route('admin.messages.destroy', $message) }}" onsubmit="return confirm('Delete this message?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" style="padding:8px 16px;background:#fee2e2;color:#b91c1c;border:none;border-radius:8px;font-size:13px;font-weight:500;cursor:pointer;">Delete</button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>

        <div style="margin-top:24px;">{{ $messages->links() }}</div>
    @endif
</x-sidebar-layout>

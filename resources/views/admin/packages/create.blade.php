<x-sidebar-layout>
    <x-slot name="header">Add Package</x-slot>

    <div style="max-width:600px;">
        <div style="background:#fff;border-radius:12px;padding:32px;box-shadow:0 1px 3px rgba(0,0,0,0.06);">
            <h2 style="margin:0 0 24px;font-size:20px;font-weight:600;color:#111827;">Create New Package</h2>
            <form method="POST" action="{{ route('admin.packages.store') }}">
                @csrf
                @if($errors->any())
                <div style="background:#fef2f2;border:1px solid #fecaca;border-radius:8px;padding:12px 16px;margin-bottom:20px;">
                    <ul style="margin:0;padding-left:20px;color:#dc2626;font-size:14px;">
                        @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif
                @if(session('success'))
                <div style="background:#f0fdf4;border:1px solid #bbf7d0;border-radius:8px;padding:12px 16px;margin-bottom:20px;color:#16a34a;font-size:14px;">
                    {{ session('success') }}
                </div>
                @endif
                <div style="margin-bottom:20px;">
                    <label style="display:block;font-size:14px;font-weight:500;color:#374151;margin-bottom:8px;">Title</label>
                    <input type="text" name="title" required style="width:100%;padding:12px;border:1px solid #e5e7eb;border-radius:8px;font-size:14px;box-sizing:border-box;">
                </div>
                <div style="margin-bottom:20px;">
                    <label style="display:block;font-size:14px;font-weight:500;color:#374151;margin-bottom:8px;">Category</label>
                    <select name="category" required style="width:100%;padding:12px;border:1px solid #e5e7eb;border-radius:8px;font-size:14px;background:#fff;">
                        <option value="adventure">Adventure</option>
                        <option value="food">Food & Culture</option>
                        <option value="relax">Slow Escapes</option>
                        <option value="private">Private Moments</option>
                    </select>
                </div>
                <div style="margin-bottom:20px;">
                    <label style="display:block;font-size:14px;font-weight:500;color:#374151;margin-bottom:8px;">Description</label>
                    <textarea name="description" rows="4" style="width:100%;padding:12px;border:1px solid #e5e7eb;border-radius:8px;font-size:14px;box-sizing:border-box;resize:vertical;"></textarea>
                </div>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:20px;">
                    <div>
                        <label style="display:block;font-size:14px;font-weight:500;color:#374151;margin-bottom:8px;">Duration</label>
                        <input type="text" name="duration" placeholder="e.g. 2 hours" style="width:100%;padding:12px;border:1px solid #e5e7eb;border-radius:8px;font-size:14px;box-sizing:border-box;">
                    </div>
                    <div>
                        <label style="display:block;font-size:14px;font-weight:500;color:#374151;margin-bottom:8px;">Price (MAD)</label>
                        <input type="number" name="price" required min="0" style="width:100%;padding:12px;border:1px solid #e5e7eb;border-radius:8px;font-size:14px;box-sizing:border-box;">
                    </div>
                </div>
                <div style="margin-bottom:20px;">
                    <label style="display:block;font-size:14px;font-weight:500;color:#374151;margin-bottom:8px;">Image URL</label>
                    <input type="text" name="image" placeholder="https://..." style="width:100%;padding:12px;border:1px solid #e5e7eb;border-radius:8px;font-size:14px;box-sizing:border-box;">
                </div>
                <div style="margin-bottom:24px;">
                    <label style="display:block;font-size:14px;font-weight:500;color:#374151;margin-bottom:8px;">Status</label>
                    <select name="status" style="width:100%;padding:12px;border:1px solid #e5e7eb;border-radius:8px;font-size:14px;background:#fff;">
                        <option value="active">Active</option>
                        <option value="draft">Draft</option>
                    </select>
                </div>
                <div style="display:flex;gap:12px;">
                    <button type="submit" style="flex:1;padding:12px;background:#f97316;color:#fff;border:none;border-radius:8px;font-size:14px;font-weight:600;cursor:pointer;">Save Package</button>
                    <a href="{{ route('admin.packages') }}" style="padding:12px 24px;background:#f3f4f6;color:#374151;border-radius:8px;font-size:14px;font-weight:500;text-decoration:none;">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</x-sidebar-layout>

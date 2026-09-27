<x-sidebar-layout>
    <x-slot name="header">Edit Package</x-slot>

    <div style="max-width:600px;">
        <div style="background:#fff;border-radius:12px;padding:32px;box-shadow:0 1px 3px rgba(0,0,0,0.06);">
            <h2 style="margin:0 0 24px;font-size:20px;font-weight:600;color:#111827;">Edit Package</h2>
            <form method="POST" action="{{ route('admin.packages.update', $package) }}">
                @csrf
                @method('PUT')
                <div style="margin-bottom:20px;">
                    <label style="display:block;font-size:14px;font-weight:500;color:#374151;margin-bottom:8px;">Title</label>
                    <input type="text" name="title" value="{{ $package->title }}" required style="width:100%;padding:12px;border:1px solid #e5e7eb;border-radius:8px;font-size:14px;box-sizing:border-box;">
                </div>
                <div style="margin-bottom:20px;">
                    <label style="display:block;font-size:14px;font-weight:500;color:#374151;margin-bottom:8px;">Category</label>
                    <select name="category" required style="width:100%;padding:12px;border:1px solid #e5e7eb;border-radius:8px;font-size:14px;background:#fff;">
                        <option value="adventure" {{ $package->category === 'adventure' ? 'selected' : '' }}>Adventure</option>
                        <option value="food" {{ $package->category === 'food' ? 'selected' : '' }}>Food & Culture</option>
                        <option value="relax" {{ $package->category === 'relax' ? 'selected' : '' }}>Slow Escapes</option>
                        <option value="private" {{ $package->category === 'private' ? 'selected' : '' }}>Private Moments</option>
                    </select>
                </div>
                <div style="margin-bottom:20px;">
                    <label style="display:block;font-size:14px;font-weight:500;color:#374151;margin-bottom:8px;">Description</label>
                    <textarea name="description" rows="4" style="width:100%;padding:12px;border:1px solid #e5e7eb;border-radius:8px;font-size:14px;box-sizing:border-box;resize:vertical;">{{ $package->description }}</textarea>
                </div>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:20px;">
                    <div>
                        <label style="display:block;font-size:14px;font-weight:500;color:#374151;margin-bottom:8px;">Duration</label>
                        <input type="text" name="duration" value="{{ $package->duration }}" style="width:100%;padding:12px;border:1px solid #e5e7eb;border-radius:8px;font-size:14px;box-sizing:border-box;">
                    </div>
                    <div>
                        <label style="display:block;font-size:14px;font-weight:500;color:#374151;margin-bottom:8px;">Price (MAD)</label>
                        <input type="number" name="price" value="{{ $package->price }}" required min="0" style="width:100%;padding:12px;border:1px solid #e5e7eb;border-radius:8px;font-size:14px;box-sizing:border-box;">
                    </div>
                </div>
                <div style="margin-bottom:20px;">
                    <label style="display:block;font-size:14px;font-weight:500;color:#374151;margin-bottom:8px;">Image URL</label>
                    <input type="text" name="image" value="{{ $package->image }}" style="width:100%;padding:12px;border:1px solid #e5e7eb;border-radius:8px;font-size:14px;box-sizing:border-box;">
                </div>
                <div style="margin-bottom:24px;">
                    <label style="display:block;font-size:14px;font-weight:500;color:#374151;margin-bottom:8px;">Status</label>
                    <select name="status" style="width:100%;padding:12px;border:1px solid #e5e7eb;border-radius:8px;font-size:14px;background:#fff;">
                        <option value="active" {{ $package->status === 'active' ? 'selected' : '' }}>Active</option>
                        <option value="draft" {{ $package->status === 'draft' ? 'selected' : '' }}>Draft</option>
                    </select>
                </div>
                <div style="display:flex;gap:12px;">
                    <button type="submit" style="flex:1;padding:12px;background:#f97316;color:#fff;border:none;border-radius:8px;font-size:14px;font-weight:600;cursor:pointer;">Update Package</button>
                    <a href="{{ route('admin.packages') }}" style="padding:12px 24px;background:#f3f4f6;color:#374151;border-radius:8px;font-size:14px;font-weight:500;text-decoration:none;">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</x-sidebar-layout>

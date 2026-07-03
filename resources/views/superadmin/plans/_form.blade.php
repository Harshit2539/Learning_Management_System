<div class="row g-3">
    <div class="col-md-6">
        <label class="sa-form-label">Plan Name <span style="color:var(--danger)">*</span></label>
        <input type="text" name="name" class="sa-form-control" value="{{ old('name', $plan?->name) }}" required>
    </div>
    <div class="col-md-6">
        <label class="sa-form-label">Slug <span style="color:var(--danger)">*</span></label>
        <input type="text" name="slug" class="sa-form-control" value="{{ old('slug', $plan?->slug) }}"
               {{ $plan ? 'readonly' : 'required' }} placeholder="e.g. professional">
        @if($plan) <small style="color:var(--text-muted);font-size:11px;">Slug cannot be changed after creation.</small> @endif
    </div>
    <div class="col-12">
        <label class="sa-form-label">Description</label>
        <textarea name="description" class="sa-form-control" rows="2">{{ old('description', $plan?->description) }}</textarea>
    </div>

    <div style="margin-top:8px;padding-top:16px;border-top:1px solid var(--border);grid-column:1/-1;" class="col-12">
        <div style="font-size:12px;font-weight:700;color:var(--text-secondary);text-transform:uppercase;letter-spacing:.06em;margin-bottom:12px;">Pricing</div>
    </div>
    <div class="col-md-4">
        <label class="sa-form-label">Monthly Price ($) <span style="color:var(--danger)">*</span></label>
        <input type="number" name="price_monthly" class="sa-form-control" step="0.01" min="0" value="{{ old('price_monthly', $plan?->price_monthly) }}" required>
    </div>
    <div class="col-md-4">
        <label class="sa-form-label">Yearly Price ($) <span style="color:var(--danger)">*</span></label>
        <input type="number" name="price_yearly" class="sa-form-control" step="0.01" min="0" value="{{ old('price_yearly', $plan?->price_yearly) }}" required>
    </div>
    <div class="col-md-4">
        <label class="sa-form-label">Sort Order</label>
        <input type="number" name="sort_order" class="sa-form-control" value="{{ old('sort_order', $plan?->sort_order ?? 0) }}">
    </div>

    <div style="margin-top:8px;padding-top:16px;border-top:1px solid var(--border);grid-column:1/-1;" class="col-12">
        <div style="font-size:12px;font-weight:700;color:var(--text-secondary);text-transform:uppercase;letter-spacing:.06em;margin-bottom:12px;">Limits <small style="font-weight:400;text-transform:none;letter-spacing:0;">(leave blank = unlimited)</small></div>
    </div>
    <div class="col-md-3">
        <label class="sa-form-label">Max Students</label>
        <input type="number" name="max_students" class="sa-form-control" value="{{ old('max_students', $plan?->max_students) }}" placeholder="∞">
    </div>
    <div class="col-md-3">
        <label class="sa-form-label">Max Instructors</label>
        <input type="number" name="max_instructors" class="sa-form-control" value="{{ old('max_instructors', $plan?->max_instructors) }}" placeholder="∞">
    </div>
    <div class="col-md-3">
        <label class="sa-form-label">Max Courses</label>
        <input type="number" name="max_courses" class="sa-form-control" value="{{ old('max_courses', $plan?->max_courses) }}" placeholder="∞">
    </div>
    <div class="col-md-3">
        <label class="sa-form-label">Storage (GB)</label>
        <input type="number" name="max_storage_gb" class="sa-form-control" value="{{ old('max_storage_gb', $plan?->max_storage_gb) }}" placeholder="∞">
    </div>

    <div style="margin-top:8px;padding-top:16px;border-top:1px solid var(--border);grid-column:1/-1;" class="col-12">
        <div style="font-size:12px;font-weight:700;color:var(--text-secondary);text-transform:uppercase;letter-spacing:.06em;margin-bottom:12px;">Features</div>
        <div style="display:flex;flex-wrap:wrap;gap:12px;">
            @foreach(['feature_zoom' => 'Zoom Live Classes', 'feature_ai' => 'AI Tools', 'feature_store' => 'Product Store', 'feature_live_class' => 'BBB / Agora Live'] as $key => $label)
            <label style="display:flex;align-items:center;gap:8px;cursor:pointer;padding:10px 14px;border:1px solid var(--border);border-radius:8px;font-size:13px;font-weight:500;transition:all .15s;"
                   onmouseover="this.style.borderColor='var(--accent)'" onmouseout="this.style.borderColor='var(--border)'">
                <input type="checkbox" name="{{ $key }}" value="1"
                    {{ old($key, $plan?->hasFeature(str_replace('feature_', '', $key)) ? '1' : '0') == '1' ? 'checked' : '' }}
                    style="accent-color:var(--accent);width:15px;height:15px;">
                {{ $label }}
            </label>
            @endforeach
        </div>
    </div>

    <div class="col-12">
        <label style="display:flex;align-items:center;gap:8px;cursor:pointer;font-size:13px;font-weight:500;">
            <input type="checkbox" name="is_featured" value="1"
                {{ old('is_featured', $plan?->is_featured) ? 'checked' : '' }}
                style="accent-color:var(--accent);width:15px;height:15px;">
            Mark as Featured <span style="color:var(--text-muted);font-weight:400;">(shows "Most Popular" badge on pricing page)</span>
        </label>
    </div>
</div>

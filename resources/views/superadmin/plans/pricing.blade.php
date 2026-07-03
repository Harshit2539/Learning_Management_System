@extends('superadmin.layouts.app')
@section('title', 'Pricing Page')
@section('page-title', 'Pricing Page')

@section('content')
<div class="page-header d-flex align-items-start justify-content-between">
    <div>
        <h1>Pricing Page</h1>
        <p>Live preview of the public-facing pricing page for your active plans.</p>
    </div>
    <div style="display:flex;gap:8px;">
        <a href="{{ url('/saas/pricing') }}" target="_blank" class="btn-sa btn-sa-outline">
            <i class="bi bi-box-arrow-up-right"></i> Open Public Page
        </a>
        <a href="{{ route('superadmin.plans.create') }}" class="btn-sa btn-sa-primary">
            <i class="bi bi-plus-lg"></i> New Plan
        </a>
    </div>
</div>

{{-- Billing toggle --}}
<div style="text-align:center;margin-bottom:36px;">
    <div style="display:inline-flex;background:var(--card-bg);border:1px solid var(--border);border-radius:10px;padding:4px;gap:4px;">
        <button id="btn-monthly" onclick="setBilling('monthly')"
            style="padding:7px 22px;border-radius:7px;border:none;font-size:13px;font-weight:600;cursor:pointer;transition:all .15s;background:var(--accent);color:#fff;">
            Monthly
        </button>
        <button id="btn-yearly" onclick="setBilling('yearly')"
            style="padding:7px 22px;border-radius:7px;border:none;font-size:13px;font-weight:600;cursor:pointer;transition:all .15s;background:transparent;color:var(--text-secondary);">
            Yearly <span style="font-size:11px;background:#dcfce7;color:#15803d;padding:2px 6px;border-radius:20px;margin-left:4px;">Save 20%</span>
        </button>
    </div>
</div>

{{-- Plans grid --}}
<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(260px,1fr));gap:20px;max-width:1100px;margin:0 auto;">
    @forelse($plans as $plan)
    <div class="sa-card" style="position:relative;{{ $plan->is_featured ? 'border-color:var(--accent);box-shadow:0 0 0 2px var(--accent-glow);' : '' }}">

        @if($plan->is_featured)
        <div style="position:absolute;top:-1px;left:50%;transform:translateX(-50%);background:var(--accent);color:#fff;font-size:11px;font-weight:700;padding:3px 14px;border-radius:0 0 8px 8px;letter-spacing:.05em;">
            MOST POPULAR
        </div>
        @endif

        <div style="padding:28px 24px 20px;">
            {{-- Plan name & description --}}
            <div style="font-size:18px;font-weight:800;color:var(--text-primary);margin-bottom:4px;">{{ $plan->name }}</div>
            <div style="font-size:13px;color:var(--text-muted);min-height:36px;">{{ $plan->description }}</div>

            {{-- Price --}}
            <div style="margin:20px 0 24px;">
                <div class="price-monthly" style="display:flex;align-items:baseline;gap:4px;">
                    <span style="font-size:38px;font-weight:900;color:var(--text-primary);">${{ number_format($plan->price_monthly, 0) }}</span>
                    <span style="font-size:13px;color:var(--text-muted);">/month</span>
                </div>
                <div class="price-yearly" style="display:none;align-items:baseline;gap:4px;">
                    <span style="font-size:38px;font-weight:900;color:var(--text-primary);">${{ number_format($plan->price_yearly / 12, 0) }}</span>
                    <span style="font-size:13px;color:var(--text-muted);">/month</span>
                </div>
                <div class="price-yearly-note" style="display:none;font-size:12px;color:var(--text-muted);margin-top:2px;">
                    Billed ${{ number_format($plan->price_yearly, 0) }}/year
                </div>
            </div>

            {{-- CTA --}}
            <a href="{{ route('superadmin.plans.edit', $plan->id) }}"
               class="btn-sa {{ $plan->is_featured ? 'btn-sa-primary' : 'btn-sa-outline' }}"
               style="width:100%;justify-content:center;margin-bottom:24px;">
                <i class="bi bi-pencil"></i> Edit Plan
            </a>

            {{-- Limits --}}
            <div style="border-top:1px solid var(--border);padding-top:20px;display:flex;flex-direction:column;gap:10px;">
                @php
                    $limits = [
                        ['icon'=>'bi-people-fill',   'label'=> $plan->max_students    ? number_format($plan->max_students).' Students'    : 'Unlimited Students'],
                        ['icon'=>'bi-person-video3', 'label'=> $plan->max_instructors ? number_format($plan->max_instructors).' Instructors': 'Unlimited Instructors'],
                        ['icon'=>'bi-book-fill',     'label'=> $plan->max_courses     ? number_format($plan->max_courses).' Courses'       : 'Unlimited Courses'],
                        ['icon'=>'bi-hdd-fill',      'label'=> $plan->max_storage_gb  ? $plan->max_storage_gb.'GB Storage'                 : 'Unlimited Storage'],
                    ];
                @endphp
                @foreach($limits as $l)
                <div style="display:flex;align-items:center;gap:10px;font-size:13px;color:var(--text-secondary);">
                    <i class="bi {{ $l['icon'] }}" style="color:var(--accent);width:16px;text-align:center;"></i>
                    {{ $l['label'] }}
                </div>
                @endforeach

                {{-- Feature badges --}}
                @php
                    $featureMap = ['zoom'=>['label'=>'Zoom','icon'=>'bi-camera-video-fill','bg'=>'#e0f2fe','color'=>'#0369a1'],
                                   'ai'=>['label'=>'AI Tools','icon'=>'bi-stars','bg'=>'#f3e8ff','color'=>'#7c3aed'],
                                   'store'=>['label'=>'Store','icon'=>'bi-shop','bg'=>'#dcfce7','color'=>'#15803d'],
                                   'live_class'=>['label'=>'Live Class','icon'=>'bi-broadcast','bg'=>'#fef9c3','color'=>'#854d0e']];
                @endphp
                @foreach($featureMap as $key => $f)
                    @if($plan->hasFeature($key))
                    <div style="display:flex;align-items:center;gap:10px;font-size:13px;color:var(--text-secondary);">
                        <i class="bi {{ $f['icon'] }}" style="color:{{ $f['color'] }};width:16px;text-align:center;"></i>
                        {{ $f['label'] }}
                    </div>
                    @endif
                @endforeach
            </div>
        </div>

        {{-- Footer status --}}
        <div style="padding:12px 24px;border-top:1px solid var(--border);background:#f8fafc;display:flex;align-items:center;justify-content:space-between;">
            <span class="sa-badge {{ $plan->is_active ? 'active' : 'suspended' }}">
                {{ $plan->is_active ? 'Active' : 'Inactive' }}
            </span>
            <a href="{{ route('superadmin.plans.toggle', $plan->id) }}"
               onclick="return confirm('Toggle plan status?')"
               class="btn-sa btn-sa-sm {{ $plan->is_active ? 'btn-sa-danger' : 'btn-sa-success' }}">
                {{ $plan->is_active ? 'Disable' : 'Enable' }}
            </a>
        </div>
    </div>
    @empty
    <div class="sa-card" style="grid-column:1/-1;text-align:center;padding:64px;">
        <i class="bi bi-layers" style="font-size:40px;display:block;margin-bottom:12px;opacity:.3;"></i>
        <p style="color:var(--text-muted);margin:0 0 16px;">No active plans yet.</p>
        <a href="{{ route('superadmin.plans.create') }}" class="btn-sa btn-sa-primary">
            <i class="bi bi-plus-lg"></i> Create First Plan
        </a>
    </div>
    @endforelse
</div>
@endsection

@section('scripts')
<script>
function setBilling(type) {
    const isYearly = type === 'yearly';
    document.querySelectorAll('.price-monthly').forEach(el => el.style.display = isYearly ? 'none' : 'flex');
    document.querySelectorAll('.price-yearly').forEach(el => el.style.display = isYearly ? 'flex' : 'none');
    document.querySelectorAll('.price-yearly-note').forEach(el => el.style.display = isYearly ? 'block' : 'none');

    const btnM = document.getElementById('btn-monthly');
    const btnY = document.getElementById('btn-yearly');
    btnM.style.background = isYearly ? 'transparent' : 'var(--accent)';
    btnM.style.color = isYearly ? 'var(--text-secondary)' : '#fff';
    btnY.style.background = isYearly ? 'var(--accent)' : 'transparent';
    btnY.style.color = isYearly ? '#fff' : 'var(--text-secondary)';
}
</script>
@endsection

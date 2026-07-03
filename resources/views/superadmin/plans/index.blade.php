@extends('superadmin.layouts.app')
@section('title', 'Plans')
@section('page-title', 'Subscription Plans')

@section('content')
<div class="page-header d-flex align-items-start justify-content-between">
    <div>
        <h1>Subscription Plans</h1>
        <p>Manage the plans available to organizations.</p>
    </div>
    <a href="{{ route('superadmin.plans.create') }}" class="btn-sa btn-sa-primary">
        <i class="bi bi-plus-lg"></i> New Plan
    </a>
</div>

<div class="sa-card">
    <div class="table-responsive">
        <table class="sa-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Plan</th>
                    <th>Monthly</th>
                    <th>Yearly</th>
                    <th>Limits</th>
                    <th>Features</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($plans as $plan)
                <tr>
                    <td style="color:var(--text-muted);font-weight:600;">{{ $plan->sort_order }}</td>
                    <td>
                        <div style="font-weight:700;color:var(--text-primary);">{{ $plan->name }}</div>
                        <div style="font-size:12px;color:var(--text-muted);">{{ $plan->description }}</div>
                        @if($plan->is_featured)
                            <span style="font-size:10px;background:var(--accent);color:#fff;padding:2px 7px;border-radius:20px;font-weight:700;">FEATURED</span>
                        @endif
                    </td>
                    <td style="font-weight:700;color:var(--text-primary);">${{ number_format($plan->price_monthly, 2) }}</td>
                    <td style="color:var(--text-secondary);">${{ number_format($plan->price_yearly, 2) }}</td>
                    <td>
                        <div style="font-size:12px;color:var(--text-secondary);line-height:1.8;">
                            <div>👤 {{ $plan->max_students ? number_format($plan->max_students).' students' : '∞ students' }}</div>
                            <div>📚 {{ $plan->max_courses ?? '∞' }} courses</div>
                            <div>💾 {{ $plan->max_storage_gb ? $plan->max_storage_gb.'GB' : '∞' }} storage</div>
                        </div>
                    </td>
                    <td>
                        <div style="display:flex;flex-wrap:wrap;gap:4px;">
                            @if($plan->hasFeature('zoom'))
                                <span style="font-size:11px;background:#e0f2fe;color:#0369a1;padding:2px 7px;border-radius:5px;font-weight:600;">Zoom</span>
                            @endif
                            @if($plan->hasFeature('ai'))
                                <span style="font-size:11px;background:#f3e8ff;color:#7c3aed;padding:2px 7px;border-radius:5px;font-weight:600;">AI</span>
                            @endif
                            @if($plan->hasFeature('store'))
                                <span style="font-size:11px;background:#dcfce7;color:#15803d;padding:2px 7px;border-radius:5px;font-weight:600;">Store</span>
                            @endif
                            @if($plan->hasFeature('live_class'))
                                <span style="font-size:11px;background:#fef9c3;color:#854d0e;padding:2px 7px;border-radius:5px;font-weight:600;">Live</span>
                            @endif
                        </div>
                    </td>
                    <td>
                        <span class="sa-badge {{ $plan->is_active ? 'active' : 'suspended' }}">
                            {{ $plan->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
                    <td>
                        <div style="display:flex;gap:6px;">
                            <a href="{{ route('superadmin.plans.edit', $plan->id) }}" class="btn-sa btn-sa-outline btn-sa-sm">
                                <i class="bi bi-pencil"></i> Edit
                            </a>
                            <a href="{{ route('superadmin.plans.toggle', $plan->id) }}"
                               class="btn-sa btn-sa-sm {{ $plan->is_active ? 'btn-sa-danger' : 'btn-sa-success' }}"
                               onclick="return confirm('Toggle plan status?')">
                                {{ $plan->is_active ? 'Disable' : 'Enable' }}
                            </a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" style="text-align:center;padding:48px;color:var(--text-muted);">
                        <i class="bi bi-layers" style="font-size:32px;display:block;margin-bottom:8px;opacity:.4;"></i>
                        No plans yet. <a href="{{ route('superadmin.plans.create') }}" style="color:var(--accent);font-weight:600;">Create one</a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

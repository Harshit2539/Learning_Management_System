@extends('superadmin.layouts.app')
@section('title', 'Organizations')
@section('page-title', 'Organizations')

@section('content')
<div class="page-header d-flex align-items-start justify-content-between">
    <div>
        <h1>Organizations</h1>
        <p>All registered organizations on the platform.</p>
    </div>
    <a href="{{ route('superadmin.organizations.create') }}" class="btn-sa btn-sa-primary">
        <i class="bi bi-plus-lg"></i> Add Organization
    </a>
</div>

{{-- Filters --}}
<div class="sa-card mb-3">
    <div style="padding:16px 20px;">
        <form method="GET" style="display:flex;gap:10px;align-items:flex-end;flex-wrap:wrap;">
            <div style="flex:1;min-width:200px;">
                <label class="sa-form-label">Search</label>
                <input type="text" name="search" class="sa-form-control" placeholder="Name or slug..." value="{{ request('search') }}">
            </div>
            <div style="min-width:160px;">
                <label class="sa-form-label">Status</label>
                <select name="status" class="sa-form-control">
                    <option value="">All Statuses</option>
                    @foreach(['active','trial','suspended','pending'] as $s)
                        <option value="{{ $s }}" {{ request('status') === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                    @endforeach
                </select>
            </div>
            <div style="display:flex;gap:8px;">
                <button type="submit" class="btn-sa btn-sa-primary">Filter</button>
                <a href="{{ route('superadmin.organizations.index') }}" class="btn-sa btn-sa-outline">Reset</a>
            </div>
        </form>
    </div>
</div>

<div class="sa-card">
    <div class="table-responsive">
        <table class="sa-table">
            <thead>
                <tr>
                    <th>Organization</th>
                    <th>Owner</th>
                    <th>Slug</th>
                    <th>Plan</th>
                    <th>Expires</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($organizations as $org)
                <tr>
                    <td>
                        <div style="font-weight:700;color:var(--text-primary);">{{ $org->name }}</div>
                        <div style="font-size:11px;color:var(--text-muted);">Created {{ date('M d, Y', $org->created_at) }}</div>
                    </td>
                    <td>
                        <div style="font-weight:500;">{{ $org->owner->full_name ?? '—' }}</div>
                        <div style="font-size:12px;color:var(--text-muted);">{{ $org->owner->email ?? '' }}</div>
                    </td>
                    <td><code style="background:#f1f5f9;padding:3px 7px;border-radius:5px;font-size:12px;">{{ $org->subdomain }}</code></td>
                    <td>
                        @if($org->activeSubscription?->plan)
                            <span style="font-weight:600;color:var(--accent);">{{ $org->activeSubscription->plan->name }}</span>
                        @else
                            <span style="color:var(--text-muted);font-style:italic;">None</span>
                        @endif
                    </td>
                    <td style="font-size:12px;">
                        @if($org->activeSubscription)
                            <span style="color:{{ $org->activeSubscription->expires_at < time() ? 'var(--danger)' : 'var(--text-muted)' }}">
                                {{ date('M d, Y', $org->activeSubscription->expires_at) }}
                            </span>
                        @elseif($org->trial_ends_at)
                            <span style="color:var(--warning);">Trial → {{ date('M d, Y', $org->trial_ends_at) }}</span>
                        @else
                            <span style="color:var(--text-muted);">—</span>
                        @endif
                    </td>
                    <td>
                        <span class="sa-badge {{ $org->status }}">{{ ucfirst($org->status) }}</span>
                    </td>
                    <td>
                        <div style="display:flex;gap:6px;">
                            <a href="{{ route('superadmin.organizations.show', $org->id) }}" class="btn-sa btn-sa-outline btn-sa-sm">View</a>
                            <a href="{{ route('superadmin.organizations.toggleStatus', $org->id) }}"
                               class="btn-sa btn-sa-sm {{ $org->status === 'active' ? 'btn-sa-danger' : 'btn-sa-success' }}"
                               onclick="return confirm('Change status?')">
                                {{ $org->status === 'active' ? 'Suspend' : 'Activate' }}
                            </a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="text-align:center;padding:48px;color:var(--text-muted);">
                        <i class="bi bi-building" style="font-size:32px;display:block;margin-bottom:8px;opacity:.4;"></i>
                        No organizations found.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($organizations->hasPages())
    <div style="padding:14px 20px;border-top:1px solid var(--border);">
        {{ $organizations->withQueryString()->links() }}
    </div>
    @endif
</div>
@endsection

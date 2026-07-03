@extends('superadmin.layouts.app')
@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')

<div class="page-header">
    <h1>Dashboard</h1>
    <p>Welcome back, {{ auth()->user()->full_name }}. Here's what's happening.</p>
</div>

{{-- Stat Cards --}}
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-icon indigo"><i class="bi bi-building-fill"></i></div>
            <div class="stat-body">
                <div class="stat-value">{{ $stats['total_organizations'] }}</div>
                <div class="stat-label">Total Organizations</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-icon green"><i class="bi bi-check-circle-fill"></i></div>
            <div class="stat-body">
                <div class="stat-value">{{ $stats['active_organizations'] }}</div>
                <div class="stat-label">Active Organizations</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-icon amber"><i class="bi bi-clock-fill"></i></div>
            <div class="stat-body">
                <div class="stat-value">{{ $stats['trial_organizations'] }}</div>
                <div class="stat-label">On Trial</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-icon blue"><i class="bi bi-currency-dollar"></i></div>
            <div class="stat-body">
                <div class="stat-value">${{ number_format($stats['total_revenue'], 0) }}</div>
                <div class="stat-label">Total Revenue</div>
            </div>
        </div>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-icon indigo"><i class="bi bi-layers-fill"></i></div>
            <div class="stat-body">
                <div class="stat-value">{{ $stats['total_plans'] }}</div>
                <div class="stat-label">Active Plans</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-icon green"><i class="bi bi-patch-check-fill"></i></div>
            <div class="stat-body">
                <div class="stat-value">{{ $stats['active_subscriptions'] }}</div>
                <div class="stat-label">Active Subscriptions</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-icon red"><i class="bi bi-slash-circle-fill"></i></div>
            <div class="stat-body">
                <div class="stat-value">{{ $stats['suspended'] }}</div>
                <div class="stat-label">Suspended</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-icon amber"><i class="bi bi-graph-up-arrow"></i></div>
            <div class="stat-body">
                <div class="stat-value">{{ $stats['total_organizations'] > 0 ? round(($stats['active_organizations'] / $stats['total_organizations']) * 100) : 0 }}%</div>
                <div class="stat-label">Activation Rate</div>
            </div>
        </div>
    </div>
</div>

{{-- Recent Organizations --}}
<div class="sa-card">
    <div class="sa-card-header">
        <span class="title"><i class="bi bi-building-fill me-2 text-muted"></i>Recent Organizations</span>
        <a href="{{ route('superadmin.organizations.create') }}" class="btn-sa btn-sa-primary btn-sa-sm">
            <i class="bi bi-plus-lg"></i> Add Organization
        </a>
    </div>
    <div class="table-responsive">
        <table class="sa-table">
            <thead>
                <tr>
                    <th>Organization</th>
                    <th>Owner</th>
                    <th>Slug</th>
                    <th>Plan</th>
                    <th>Status</th>
                    <th>Created</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse($recentOrganizations as $org)
                <tr>
                    <td>
                        <div style="font-weight:600;color:var(--text-primary);">{{ $org->name }}</div>
                    </td>
                    <td style="color:var(--text-secondary);">{{ $org->owner->full_name ?? '—' }}</td>
                    <td><code style="background:#f1f5f9;padding:3px 7px;border-radius:5px;font-size:12px;">{{ $org->subdomain }}</code></td>
                    <td>
                        @if($org->activeSubscription?->plan)
                            <span style="font-weight:600;color:var(--accent);">{{ $org->activeSubscription->plan->name }}</span>
                        @else
                            <span style="color:var(--text-muted);">No plan</span>
                        @endif
                    </td>
                    <td>
                        <span class="sa-badge {{ $org->status }}">{{ ucfirst($org->status) }}</span>
                    </td>
                    <td style="color:var(--text-muted);font-size:12px;">{{ date('M d, Y', $org->created_at) }}</td>
                    <td>
                        <a href="{{ route('superadmin.organizations.show', $org->id) }}" class="btn-sa btn-sa-outline btn-sa-sm">
                            View <i class="bi bi-arrow-right"></i>
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="text-align:center;padding:48px;color:var(--text-muted);">
                        <i class="bi bi-building" style="font-size:32px;display:block;margin-bottom:8px;opacity:.4;"></i>
                        No organizations yet.
                        <a href="{{ route('superadmin.organizations.create') }}" style="color:var(--accent);font-weight:600;margin-left:4px;">Create one</a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection

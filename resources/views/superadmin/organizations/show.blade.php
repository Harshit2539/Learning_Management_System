@extends('superadmin.layouts.app')
@section('title', $org->name)
@section('page-title', $org->name)

@section('content')
<div class="page-header d-flex align-items-start justify-content-between">
    <div>
        <h1>{{ $org->name }}</h1>
        <p>Organization details, subscription and history.</p>
    </div>
    <a href="{{ route('superadmin.organizations.index') }}" class="btn-sa btn-sa-outline">
        <i class="bi bi-arrow-left"></i> Back
    </a>
</div>

<div class="row g-4">

    {{-- Left --}}
    <div class="col-md-4">
        <div class="sa-card mb-3">
            <div class="sa-card-header"><span class="title"><i class="bi bi-building-fill me-2 text-muted"></i>Organization</span></div>
            <div style="padding:20px;">
                <dl style="margin:0;">
                    <dt style="font-size:11px;color:var(--text-muted);font-weight:600;text-transform:uppercase;margin-bottom:2px;">Name</dt>
                    <dd style="font-weight:700;color:var(--text-primary);margin-bottom:14px;">{{ $org->name }}</dd>

                    <dt style="font-size:11px;color:var(--text-muted);font-weight:600;text-transform:uppercase;margin-bottom:2px;">Slug</dt>
                    <dd style="margin-bottom:14px;"><code style="background:#f1f5f9;padding:3px 8px;border-radius:5px;">{{ $org->subdomain }}</code></dd>

                    <dt style="font-size:11px;color:var(--text-muted);font-weight:600;text-transform:uppercase;margin-bottom:2px;">Status</dt>
                    <dd style="margin-bottom:14px;"><span class="sa-badge {{ $org->status }}">{{ ucfirst($org->status) }}</span></dd>

                    <dt style="font-size:11px;color:var(--text-muted);font-weight:600;text-transform:uppercase;margin-bottom:2px;">Created</dt>
                    <dd style="color:var(--text-secondary);margin-bottom:0;">{{ date('M d, Y', $org->created_at) }}</dd>

                    @if($org->trial_ends_at)
                    <dt style="font-size:11px;color:var(--text-muted);font-weight:600;text-transform:uppercase;margin-bottom:2px;margin-top:14px;">Trial Ends</dt>
                    <dd style="color:var(--warning);margin-bottom:0;">{{ date('M d, Y', $org->trial_ends_at) }}</dd>
                    @endif
                </dl>

                <div style="border-top:1px solid var(--border);margin-top:16px;padding-top:16px;">
                    <div style="font-size:11px;color:var(--text-muted);font-weight:600;text-transform:uppercase;margin-bottom:10px;">Owner</div>
                    <div style="font-weight:600;">{{ $org->owner->full_name ?? '—' }}</div>
                    <div style="font-size:12px;color:var(--text-muted);word-break:break-all;">{{ $org->owner->email ?? '—' }}</div>
                </div>

                <div style="border-top:1px solid var(--border);margin-top:16px;padding-top:16px;display:flex;gap:8px;">
                    <a href="{{ route('superadmin.organizations.toggleStatus', $org->id) }}"
                       class="btn-sa btn-sa-sm {{ $org->status === 'active' ? 'btn-sa-danger' : 'btn-sa-success' }}"
                       style="flex:1;justify-content:center;"
                       onclick="return confirm('Change status?')">
                        {{ $org->status === 'active' ? 'Suspend' : 'Activate' }}
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- Right --}}
    <div class="col-md-8">

        {{-- Active Plan --}}
        @if($org->activeSubscription)
        <div class="sa-card mb-3" style="border-left:4px solid var(--success);">
            <div class="sa-card-header"><span class="title"><i class="bi bi-patch-check-fill me-2" style="color:var(--success)"></i>Active Subscription</span></div>
            <div style="padding:20px;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px;">
                <div>
                    <div style="font-size:20px;font-weight:800;color:var(--text-primary);">{{ $org->activeSubscription->plan->name }}</div>
                    <div style="font-size:13px;color:var(--text-muted);">{{ ucfirst($org->activeSubscription->billing_cycle) }} billing &bull; ${{ number_format($org->activeSubscription->amount_paid, 2) }} paid</div>
                    <div style="font-size:12px;color:var(--text-muted);margin-top:4px;">
                        {{ date('M d, Y', $org->activeSubscription->starts_at) }} → {{ date('M d, Y', $org->activeSubscription->expires_at) }}
                    </div>
                </div>
                <span class="sa-badge active" style="font-size:13px;padding:6px 14px;">Active</span>
            </div>
        </div>
        @else
        <div class="sa-alert error mb-3"><i class="bi bi-exclamation-circle-fill"></i> No active subscription for this organization.</div>
        @endif

        {{-- Assign Plan --}}
        <div class="sa-card mb-3">
            <div class="sa-card-header"><span class="title"><i class="bi bi-arrow-repeat me-2 text-muted"></i>Assign / Change Plan</span></div>
            <div style="padding:20px;">
                <form action="{{ route('superadmin.organizations.assignPlan', $org->id) }}" method="POST">
                    @csrf
                    <div style="display:flex;gap:10px;flex-wrap:wrap;align-items:flex-end;">
                        <div style="flex:1;min-width:180px;">
                            <label class="sa-form-label">Plan</label>
                            <select name="plan_id" class="sa-form-control" required>
                                <option value="">Select Plan</option>
                                @foreach(\App\Models\SaasPlan::where('is_active',true)->orderBy('sort_order')->get() as $plan)
                                    <option value="{{ $plan->id }}" {{ $org->activeSubscription?->plan_id == $plan->id ? 'selected' : '' }}>
                                        {{ $plan->name }} (${{ $plan->price_monthly }}/mo)
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div style="min-width:140px;">
                            <label class="sa-form-label">Billing Cycle</label>
                            <select name="billing_cycle" class="sa-form-control">
                                <option value="monthly">Monthly</option>
                                <option value="yearly">Yearly</option>
                            </select>
                        </div>
                        <button type="submit" class="btn-sa btn-sa-primary">Assign</button>
                    </div>
                </form>
            </div>
        </div>

        {{-- History --}}
        <div class="sa-card">
            <div class="sa-card-header"><span class="title"><i class="bi bi-clock-history me-2 text-muted"></i>Subscription History</span></div>
            <div class="table-responsive">
                <table class="sa-table">
                    <thead>
                        <tr><th>Plan</th><th>Cycle</th><th>Amount</th><th>Period</th><th>Status</th></tr>
                    </thead>
                    <tbody>
                        @forelse($org->subscriptions()->with('plan')->latest('created_at')->get() as $sub)
                        <tr>
                            <td style="font-weight:600;">{{ $sub->plan->name ?? '—' }}</td>
                            <td style="color:var(--text-secondary);">{{ ucfirst($sub->billing_cycle) }}</td>
                            <td style="font-weight:600;">${{ number_format($sub->amount_paid, 2) }}</td>
                            <td style="font-size:12px;color:var(--text-muted);">{{ date('M d, Y', $sub->starts_at) }} → {{ date('M d, Y', $sub->expires_at) }}</td>
                            <td>
                                <span class="sa-badge {{ $sub->status === 'cancelled' ? 'suspended' : ($sub->status === 'expired' ? 'trial' : $sub->status) }}">
                                    {{ ucfirst($sub->status) }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="5" style="text-align:center;padding:32px;color:var(--text-muted);">No history yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>
@endsection

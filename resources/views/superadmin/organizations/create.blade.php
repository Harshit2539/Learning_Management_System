@extends('superadmin.layouts.app')
@section('title', 'Add Organization')
@section('page-title', 'Add Organization')

@section('content')
<div class="page-header">
    <h1>Add Organization</h1>
    <p>Create a new organization and its admin account.</p>
</div>

<div class="row justify-content-center">
<div class="col-lg-8">
<div class="sa-card p-4">
    @if($errors->any())
        <div class="sa-alert error"><i class="bi bi-exclamation-circle-fill"></i> {{ $errors->first() }}</div>
    @endif

    <form action="{{ route('superadmin.organizations.store') }}" method="POST">
        @csrf

        <div style="font-size:12px;font-weight:700;color:var(--text-secondary);text-transform:uppercase;letter-spacing:.06em;margin-bottom:12px;">Organization Details</div>
        <div class="row g-3 mb-4">
            <div class="col-md-6">
                <label class="sa-form-label">Organization Name <span style="color:var(--danger)">*</span></label>
                <input type="text" name="name" class="sa-form-control" value="{{ old('name') }}" required>
            </div>
            <div class="col-md-6">
                <label class="sa-form-label">Organization Slug <span style="color:var(--danger)">*</span></label>
                <input type="text" name="subdomain" class="sa-form-control" value="{{ old('subdomain') }}"
                       required pattern="[a-z0-9\-]+" oninput="this.value=this.value.toLowerCase()" placeholder="company-name">
                <small style="color:var(--text-muted);font-size:11px;">Lowercase, hyphens only. Unique identifier.</small>
            </div>
        </div>

        <div style="font-size:12px;font-weight:700;color:var(--text-secondary);text-transform:uppercase;letter-spacing:.06em;margin-bottom:12px;padding-top:16px;border-top:1px solid var(--border);">Admin Account</div>
        <div class="row g-3 mb-4">
            <div class="col-md-6">
                <label class="sa-form-label">Full Name <span style="color:var(--danger)">*</span></label>
                <input type="text" name="owner_name" class="sa-form-control" value="{{ old('owner_name') }}" required>
            </div>
            <div class="col-md-6">
                <label class="sa-form-label">Email Address <span style="color:var(--danger)">*</span></label>
                <input type="email" name="owner_email" class="sa-form-control" value="{{ old('owner_email') }}" required>
            </div>
            <div class="col-md-6">
                <label class="sa-form-label">Password <span style="color:var(--danger)">*</span></label>
                <input type="password" name="owner_password" class="sa-form-control" required minlength="8">
            </div>
        </div>

        <div style="font-size:12px;font-weight:700;color:var(--text-secondary);text-transform:uppercase;letter-spacing:.06em;margin-bottom:12px;padding-top:16px;border-top:1px solid var(--border);">Subscription <span style="font-weight:400;text-transform:none;letter-spacing:0;">(optional — leave blank to start on trial)</span></div>
        <div class="row g-3">
            <div class="col-md-6">
                <label class="sa-form-label">Assign Plan</label>
                <select name="plan_id" class="sa-form-control">
                    <option value="">— Start on 14-day Trial —</option>
                    @foreach($plans as $plan)
                        <option value="{{ $plan->id }}" {{ old('plan_id') == $plan->id ? 'selected' : '' }}>
                            {{ $plan->name }} (${{ $plan->price_monthly }}/mo)
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-6">
                <label class="sa-form-label">Billing Cycle</label>
                <select name="billing_cycle" class="sa-form-control">
                    <option value="monthly">Monthly</option>
                    <option value="yearly">Yearly</option>
                </select>
            </div>
        </div>

        <div style="display:flex;gap:10px;margin-top:24px;padding-top:20px;border-top:1px solid var(--border);">
            <button type="submit" class="btn-sa btn-sa-primary"><i class="bi bi-check-lg"></i> Create Organization</button>
            <a href="{{ route('superadmin.organizations.index') }}" class="btn-sa btn-sa-outline">Cancel</a>
        </div>
    </form>
</div>
</div>
</div>
@endsection

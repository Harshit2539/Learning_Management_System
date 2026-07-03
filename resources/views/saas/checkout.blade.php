<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout — {{ $plan->name }} Plan</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body { background: #f4f6fb; font-family: 'Segoe UI', sans-serif; }
        .checkout-wrap { max-width: 960px; margin: 50px auto; }
        .card { border-radius: 16px; border: none; box-shadow: 0 4px 24px rgba(0,0,0,.07); }
        .plan-summary { background: linear-gradient(135deg,#667eea,#764ba2); color:#fff; border-radius: 16px; padding: 32px; }
        .plan-summary h2 { font-size: 2rem; font-weight: 700; }
        .plan-summary .price { font-size: 2.5rem; font-weight: 700; }
        .feature-badge { background: rgba(255,255,255,.2); border-radius: 8px; padding: 6px 12px; font-size:.85rem; display:inline-block; margin:4px; }
        .section-title { font-weight: 700; color: #2d3748; font-size: 1.1rem; border-left: 4px solid #667eea; padding-left: 10px; margin-bottom: 20px; }
        .btn-checkout { background: linear-gradient(135deg,#667eea,#764ba2); border:none; color:#fff; font-weight:600; padding:14px; font-size:1rem; border-radius:10px; }
        .btn-checkout:hover { opacity:.9; color:#fff; }
        .billing-switch { display: flex; gap: 8px; }
        .billing-switch .btn { border-radius: 8px; font-weight: 600; }
        .billing-switch .btn.active { background: #667eea; color: #fff; border-color: #667eea; }
        .back-link { color: #667eea; text-decoration: none; font-weight:500; }
        .back-link:hover { text-decoration: underline; }
    </style>
</head>
<body>
<div class="checkout-wrap px-3">
    <a href="{{ route('saas.pricing') }}" class="back-link d-inline-flex align-items-center mb-4">
        ← Back to plans
    </a>

    <div class="row g-4">
        {{-- Left: Summary --}}
        <div class="col-md-4">
            <div class="plan-summary sticky-top" style="top:20px;">
                <p class="mb-1 opacity-75 text-uppercase fw-semibold" style="font-size:.8rem;">Selected Plan</p>
                <h2>{{ $plan->name }}</h2>
                <p class="opacity-85 mb-3">{{ $plan->description }}</p>

                <div id="price-display">
                    <div class="price">$<span id="price-val">{{ number_format($plan->getPrice($cycle), 0) }}</span></div>
                    <div class="opacity-75">/ <span id="price-cycle">{{ $cycle }}</span></div>
                </div>

                <hr class="border-white opacity-25 my-3">

                <div class="mb-3">
                    <div class="billing-switch">
                        <button type="button" class="btn btn-sm btn-outline-light {{ $cycle === 'monthly' ? 'active' : '' }}" onclick="setCycle('monthly')">Monthly</button>
                        <button type="button" class="btn btn-sm btn-outline-light {{ $cycle === 'yearly' ? 'active' : '' }}" onclick="setCycle('yearly')">Yearly <span style="font-size:.75rem;opacity:.8;">-17%</span></button>
                    </div>
                </div>

                <div>
                    @if($plan->max_students) <span class="feature-badge">{{ number_format($plan->max_students) }} Students</span> @else <span class="feature-badge">Unlimited Students</span> @endif
                    @if($plan->max_courses) <span class="feature-badge">{{ $plan->max_courses }} Courses</span> @else <span class="feature-badge">Unlimited Courses</span> @endif
                    @if($plan->hasFeature('zoom')) <span class="feature-badge">Zoom</span> @endif
                    @if($plan->hasFeature('ai')) <span class="feature-badge">AI Tools</span> @endif
                    @if($plan->hasFeature('store')) <span class="feature-badge">Store</span> @endif
                </div>
            </div>
        </div>

        {{-- Right: Form --}}
        <div class="col-md-8">
            <div class="card p-4">
                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
                    </div>
                @endif

                <form action="{{ route('saas.checkout.process') }}" method="POST">
                    @csrf
                    <input type="hidden" name="plan_id" value="{{ $plan->id }}">
                    <input type="hidden" name="billing_cycle" id="billing_cycle_input" value="{{ $cycle }}">

                    {{-- Organization --}}
                    <div class="section-title">Organization Details</div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Organization Name <span class="text-danger">*</span></label>
                        <input type="text" name="org_name" class="form-control" value="{{ old('org_name') }}" placeholder="Acme Academy" required>
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-semibold">Organization Slug <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="text" name="subdomain" class="form-control" value="{{ old('subdomain') }}" placeholder="acme-academy" required pattern="[a-z0-9\-]+" oninput="this.value=this.value.toLowerCase()">
                        </div>
                        <small class="text-muted">Unique identifier for your organization. Lowercase letters, numbers and hyphens only.</small>
                    </div>

                    {{-- Account --}}
                    <div class="section-title">Admin Account</div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Full Name <span class="text-danger">*</span></label>
                        <input type="text" name="owner_name" class="form-control" value="{{ old('owner_name') }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Email Address <span class="text-danger">*</span></label>
                        <input type="email" name="owner_email" class="form-control" value="{{ old('owner_email') }}" required>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Password <span class="text-danger">*</span></label>
                            <input type="password" name="owner_password" class="form-control" required minlength="8">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Confirm Password <span class="text-danger">*</span></label>
                            <input type="password" name="owner_password_confirmation" class="form-control" required>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-checkout w-100 mt-2">
                        Continue to Payment →
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    const prices = { monthly: {{ $plan->price_monthly }}, yearly: {{ $plan->price_yearly }} };

    function setCycle(cycle) {
        document.getElementById('price-val').textContent = Math.round(prices[cycle]);
        document.getElementById('price-cycle').textContent = cycle;
        document.getElementById('billing_cycle_input').value = cycle;
        document.querySelectorAll('.billing-switch .btn').forEach(b => b.classList.remove('active'));
        event.target.classList.add('active');
    }
</script>
</body>
</html>

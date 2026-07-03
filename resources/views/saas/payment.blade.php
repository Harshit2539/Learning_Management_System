<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment — LMS SaaS</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body { background: #f4f6fb; font-family: 'Segoe UI', sans-serif; }
        .wrap { max-width: 680px; margin: 60px auto; padding: 0 16px; }
        .card { border-radius: 16px; border: none; box-shadow: 0 4px 24px rgba(0,0,0,.07); }
        .order-header { background: linear-gradient(135deg,#667eea,#764ba2); color:#fff; border-radius:16px 16px 0 0; padding:28px 32px; }
        .order-body { padding: 32px; }
        .row-line { display:flex; justify-content:space-between; padding:10px 0; border-bottom:1px solid #f0f0f0; font-size:.95rem; }
        .row-line:last-child { border-bottom:none; }
        .row-line .label { color:#64748b; }
        .row-line .value { font-weight:600; color:#1e293b; }
        .btn-pay { background: linear-gradient(135deg,#667eea,#764ba2); border:none; color:#fff; font-weight:700; padding:14px; font-size:1rem; border-radius:10px; }
        .btn-pay:hover { opacity:.9; color:#fff; }
        .note { background:#f8fafc; border-left:4px solid #667eea; padding:12px 16px; border-radius:6px; font-size:.85rem; color:#475569; }
    </style>
</head>
<body>
<div class="wrap">
    <div class="card">
        <div class="order-header">
            <p class="mb-1 opacity-75 small text-uppercase fw-semibold">Order Summary</p>
            <h3 class="mb-0 fw-bold">{{ $sub->plan->name }} Plan</h3>
            <p class="mb-0 opacity-85">{{ $sub->organization->name }}</p>
        </div>
        <div class="order-body">

            <div class="row-line">
                <span class="label">Organization</span>
                <span class="value">{{ $sub->organization->name }}</span>
            </div>
            <div class="row-line">
                <span class="label">Subdomain</span>
                <span class="value"><code>{{ $sub->organization->subdomain }}.yourdomain.com</code></span>
            </div>
            <div class="row-line">
                <span class="label">Plan</span>
                <span class="value">{{ $sub->plan->name }}</span>
            </div>
            <div class="row-line">
                <span class="label">Billing Cycle</span>
                <span class="value">{{ ucfirst($sub->billing_cycle) }}</span>
            </div>
            <div class="row-line">
                <span class="label">Total Amount</span>
                <span class="value fs-5">${{ number_format($sub->amount_paid, 2) }}</span>
            </div>

            <div class="note my-4">
                <strong>Note:</strong> After confirming, you can log in at
                <a href="{{ url('/login') }}">{{ url('/login') }}</a> using your registered email and password.
            </div>

            {{-- Replace this button with Stripe/PayPal when ready --}}
            <form method="GET" action="{{ route('saas.payment.success') }}">
                <input type="hidden" name="subscription_id" value="{{ $sub->id }}">
                <button type="submit" class="btn btn-pay w-100">
                    Confirm & Activate Organization →
                </button>
            </form>

            <div class="text-center mt-3">
                <a href="{{ route('saas.pricing') }}" class="text-muted small">← Back to pricing</a>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

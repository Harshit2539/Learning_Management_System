<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Choose Your Plan — LMS SaaS</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body { background: #f4f6fb; font-family: 'Segoe UI', sans-serif; }
        .hero { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: #fff; padding: 80px 0 60px; text-align: center; }
        .hero h1 { font-size: 2.8rem; font-weight: 700; }
        .hero p  { font-size: 1.1rem; opacity: .85; }
        .billing-toggle { display: flex; align-items: center; justify-content: center; gap: 12px; margin: 30px 0 10px; }
        .billing-toggle .badge-save { background: #28a745; color: #fff; font-size:.75rem; padding:3px 8px; border-radius:20px; }
        .plan-card { border-radius: 16px; border: 2px solid #e8ecf0; background: #fff; transition: transform .2s, box-shadow .2s; height: 100%; }
        .plan-card:hover { transform: translateY(-6px); box-shadow: 0 20px 40px rgba(0,0,0,.1); }
        .plan-card.featured { border-color: #667eea; position: relative; }
        .plan-card.featured::before { content: 'Most Popular'; position: absolute; top: -14px; left: 50%; transform: translateX(-50%); background: #667eea; color: #fff; font-size:.75rem; font-weight:600; padding:4px 16px; border-radius:20px; }
        .plan-price .amount { font-size: 2.8rem; font-weight: 700; color: #2d3748; }
        .plan-price .period { font-size: .9rem; color: #718096; }
        .feature-list li { padding: 6px 0; font-size: .95rem; color: #4a5568; }
        .feature-list li::before { content: '✓'; color: #667eea; font-weight: 700; margin-right: 8px; }
        .feature-list li.disabled { color: #a0aec0; }
        .feature-list li.disabled::before { content: '✗'; color: #cbd5e0; }
        .btn-plan { border-radius: 8px; font-weight: 600; padding: 12px; font-size: 1rem; }
        .btn-plan-primary { background: linear-gradient(135deg,#667eea,#764ba2); border:none; color:#fff; }
        .btn-plan-primary:hover { opacity: .9; color: #fff; }
        .btn-plan-outline { border: 2px solid #667eea; color: #667eea; background: transparent; }
        .btn-plan-outline:hover { background: #667eea; color: #fff; }
        .section-plans { padding: 60px 0; }
        .faq-section { background: #fff; padding: 60px 0; }
        footer { background: #2d3748; color: #a0aec0; text-align: center; padding: 24px; font-size: .9rem; }
    </style>
</head>
<body>

<div class="hero">
    <div class="container">
        <h1>Simple, Transparent Pricing</h1>
        <p>Choose the plan that fits your organization. Upgrade or cancel anytime.</p>

        <div class="billing-toggle">
            <span class="fw-semibold text-white">Monthly</span>
            <div class="form-check form-switch mb-0">
                <input class="form-check-input" type="checkbox" id="billingToggle" style="width:48px;height:24px;cursor:pointer;">
            </div>
            <span class="fw-semibold text-white">Yearly <span class="badge-save">Save 17%</span></span>
        </div>
    </div>
</div>

@if(session('subscription_expired'))
    <div class="container mt-4">
        <div class="alert alert-warning">{{ session('subscription_expired') }}</div>
    </div>
@endif

<section class="section-plans">
    <div class="container">
        <div class="row g-4 justify-content-center">
            @foreach($plans as $plan)
            <div class="col-md-4">
                <div class="plan-card p-4 d-flex flex-column {{ $plan->is_featured ? 'featured' : '' }}">
                    <div class="mb-3">
                        <h4 class="fw-bold mb-1">{{ $plan->name }}</h4>
                        <p class="text-muted small mb-0">{{ $plan->description }}</p>
                    </div>

                    <div class="plan-price my-3">
                        <span class="amount monthly-price" data-monthly="{{ $plan->price_monthly }}" data-yearly="{{ $plan->price_yearly }}">
                            ${{ number_format($plan->price_monthly, 0) }}
                        </span>
                        <span class="period">/ <span class="period-label">month</span></span>
                        <div class="text-muted small yearly-note d-none">Billed annually</div>
                    </div>

                    <ul class="feature-list list-unstyled flex-grow-1">
                        <li>{{ $plan->max_students ? number_format($plan->max_students) . ' Students' : 'Unlimited Students' }}</li>
                        <li>{{ $plan->max_instructors ? $plan->max_instructors . ' Instructors' : 'Unlimited Instructors' }}</li>
                        <li>{{ $plan->max_courses ? $plan->max_courses . ' Courses' : 'Unlimited Courses' }}</li>
                        <li>{{ $plan->max_storage_gb ? $plan->max_storage_gb . 'GB Storage' : 'Unlimited Storage' }}</li>
                        <li class="{{ $plan->hasFeature('zoom') ? '' : 'disabled' }}">Zoom Live Classes</li>
                        <li class="{{ $plan->hasFeature('live_class') ? '' : 'disabled' }}">BigBlueButton / Agora</li>
                        <li class="{{ $plan->hasFeature('ai') ? '' : 'disabled' }}">AI Content Tools</li>
                        <li class="{{ $plan->hasFeature('store') ? '' : 'disabled' }}">Product Store</li>
                    </ul>

                    <a href="{{ route('saas.checkout', ['slug' => $plan->slug]) }}"
                       class="btn btn-plan w-100 mt-4 {{ $plan->is_featured ? 'btn-plan-primary' : 'btn-plan-outline' }} plan-link"
                       data-slug="{{ $plan->slug }}">
                        Get Started
                    </a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<section class="faq-section">
    <div class="container" style="max-width:720px;">
        <h2 class="text-center fw-bold mb-5">Frequently Asked Questions</h2>
        <div class="accordion" id="faqAccordion">
            <div class="accordion-item border-0 mb-3 rounded shadow-sm">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">
                        Can I change my plan later?
                    </button>
                </h2>
                <div id="faq1" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                    <div class="accordion-body text-muted">Yes, you can upgrade or downgrade your plan at any time from your billing dashboard.</div>
                </div>
            </div>
            <div class="accordion-item border-0 mb-3 rounded shadow-sm">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">
                        Is there a free trial?
                    </button>
                </h2>
                <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                    <div class="accordion-body text-muted">All new organizations get a 14-day free trial with full access to Professional features.</div>
                </div>
            </div>
            <div class="accordion-item border-0 mb-3 rounded shadow-sm">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#faq3">
                        What payment methods are accepted?
                    </button>
                </h2>
                <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                    <div class="accordion-body text-muted">We accept credit/debit cards via Stripe, PayPal, Razorpay and 20+ other gateways.</div>
                </div>
            </div>
        </div>
    </div>
</section>

<footer>
    <p class="mb-0">&copy; {{ date('Y') }} LMS SaaS. All rights reserved.</p>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    const toggle = document.getElementById('billingToggle');
    toggle.addEventListener('change', function () {
        const isYearly = this.checked;
        document.querySelectorAll('.monthly-price').forEach(el => {
            const price = isYearly ? el.dataset.yearly : el.dataset.monthly;
            el.textContent = '$' + parseFloat(price).toFixed(0);
        });
        document.querySelectorAll('.period-label').forEach(el => el.textContent = isYearly ? 'year' : 'month');
        document.querySelectorAll('.yearly-note').forEach(el => el.classList.toggle('d-none', !isYearly));
        document.querySelectorAll('.plan-link').forEach(el => {
            const url = new URL(el.href);
            url.searchParams.set('cycle', isYearly ? 'yearly' : 'monthly');
            el.href = url.toString();
        });
    });
</script>
</body>
</html>

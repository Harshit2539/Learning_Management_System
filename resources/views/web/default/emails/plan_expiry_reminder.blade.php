@extends('web.default.layouts.email')

@section('body')
<td valign="top" class="bodyContent" mc:edit="body_content">

    @if($daysLeft > 0)
        <h1 class="h1" style="color:#e67e22;">⚠️ Your Plan Expires in {{ $daysLeft }} Day{{ $daysLeft > 1 ? 's' : '' }}</h1>
        <p>Hi <strong>{{ $org->owner->full_name ?? $org->name }}</strong>,</p>
        <p>This is a reminder that your <strong>{{ $plan->name }}</strong> plan for organisation <strong>{{ $org->name }}</strong> will expire on <strong style="color:#e74c3c;">{{ date('d M Y', $subscription->expires_at) }}</strong>.</p>
        <p>Please renew your plan before it expires to avoid any interruption to your organisation's access.</p>
    @else
        <h1 class="h1" style="color:#e74c3c;">🚫 Your Plan Has Expired</h1>
        <p>Hi <strong>{{ $org->owner->full_name ?? $org->name }}</strong>,</p>
        <p>Your <strong>{{ $plan->name }}</strong> plan for organisation <strong>{{ $org->name }}</strong> expired on <strong>{{ date('d M Y', $subscription->expires_at) }}</strong>.</p>
        <p>Your organisation has been <strong>suspended</strong>. Renew your plan immediately to restore full access.</p>
    @endif

    <table style="margin:16px 0;border-collapse:collapse;width:100%;">
        <tr>
            <td style="padding:8px 12px 8px 0;font-weight:bold;color:#555;width:40%;">Organisation:</td>
            <td style="padding:8px 0;">{{ $org->name }}</td>
        </tr>
        <tr>
            <td style="padding:8px 12px 8px 0;font-weight:bold;color:#555;">Plan:</td>
            <td style="padding:8px 0;">{{ $plan->name }}</td>
        </tr>
        <tr>
            <td style="padding:8px 12px 8px 0;font-weight:bold;color:#555;">Billing Cycle:</td>
            <td style="padding:8px 0;">{{ ucfirst($subscription->billing_cycle) }}</td>
        </tr>
        <tr>
            <td style="padding:8px 12px 8px 0;font-weight:bold;color:#555;">Expiry Date:</td>
            <td style="padding:8px 0;color:#e74c3c;font-weight:bold;">{{ date('d M Y', $subscription->expires_at) }}</td>
        </tr>
    </table>

    <p style="margin-top:20px;">
        <a href="{{ url('/saas/pricing') }}"
           style="background:#e74c3c;color:#fff;padding:12px 28px;border-radius:4px;text-decoration:none;font-weight:bold;font-size:15px;">
            Renew Your Plan Now
        </a>
    </p>

    <p style="margin-top:20px;color:#888;font-size:13px;">
        If you have already renewed, please ignore this email. For support, contact us at {{ env('MAIL_FROM_ADDRESS') }}.
    </p>
</td>
@endsection

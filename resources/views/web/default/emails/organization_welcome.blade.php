@extends('web.default.layouts.email')

@section('body')
<td valign="top" class="bodyContent" mc:edit="body_content">
    <h1 class="h1">Welcome, {{ $ownerName }}! 🎉</h1>

    <p>Your organisation <strong>{{ $org->name }}</strong> has been successfully created and is now active.</p>

    <p>Here are your login credentials:</p>

    <table style="margin:16px 0;border-collapse:collapse;">
        <tr>
            <td style="padding:6px 12px 6px 0;font-weight:bold;color:#555;">Email:</td>
            <td style="padding:6px 0;">{{ $ownerEmail }}</td>
        </tr>
        <tr>
            <td style="padding:6px 12px 6px 0;font-weight:bold;color:#555;">Password:</td>
            <td style="padding:6px 0;">{{ $plainPassword }}</td>
        </tr>
    </table>

    <hr style="border:none;border-top:1px solid #eee;margin:24px 0;">

    @if($plan && $subscription)
    <h2 style="font-size:18px;color:#333;">Your Plan Details</h2>

    <table style="margin:16px 0;border-collapse:collapse;width:100%;">
        <tr>
            <td style="padding:8px 12px 8px 0;font-weight:bold;color:#555;width:40%;">Plan:</td>
            <td style="padding:8px 0;">{{ $plan->name }}</td>
        </tr>
        <tr>
            <td style="padding:8px 12px 8px 0;font-weight:bold;color:#555;">Billing Cycle:</td>
            <td style="padding:8px 0;">{{ ucfirst($subscription->billing_cycle) }}</td>
        </tr>
        <tr>
            <td style="padding:8px 12px 8px 0;font-weight:bold;color:#555;">Amount Paid:</td>
            <td style="padding:8px 0;">${{ number_format($subscription->amount_paid, 2) }}</td>
        </tr>
        <tr>
            <td style="padding:8px 12px 8px 0;font-weight:bold;color:#555;">Activated On:</td>
            <td style="padding:8px 0;">{{ date('d M Y', $subscription->starts_at) }}</td>
        </tr>
        <tr>
            <td style="padding:8px 12px 8px 0;font-weight:bold;color:#555;">Renews / Expires On:</td>
            <td style="padding:8px 0;color:#e74c3c;font-weight:bold;">{{ date('d M Y', $subscription->expires_at) }}</td>
        </tr>
        @if($plan->max_students !== null || $plan->max_instructors !== null || $plan->max_courses !== null)
        <tr>
            <td colspan="2" style="padding:12px 0 4px 0;font-weight:bold;color:#555;">Plan Limits:</td>
        </tr>
        @if($plan->max_students !== null)
        <tr>
            <td style="padding:4px 12px 4px 16px;color:#777;">Max Students:</td>
            <td style="padding:4px 0;">{{ $plan->max_students }}</td>
        </tr>
        @endif
        @if($plan->max_instructors !== null)
        <tr>
            <td style="padding:4px 12px 4px 16px;color:#777;">Max Instructors:</td>
            <td style="padding:4px 0;">{{ $plan->max_instructors }}</td>
        </tr>
        @endif
        @if($plan->max_courses !== null)
        <tr>
            <td style="padding:4px 12px 4px 16px;color:#777;">Max Courses:</td>
            <td style="padding:4px 0;">{{ $plan->max_courses }}</td>
        </tr>
        @endif
        @endif
    </table>

    <p style="margin-top:20px;color:#888;font-size:13px;">
        Please remember to renew your plan before <strong>{{ date('d M Y', $subscription->expires_at) }}</strong> to avoid any interruption in service.
    </p>
    @else
    <p>Your account is currently on a <strong>free trial</strong>. You can upgrade to a paid plan at any time from your dashboard.</p>
    @endif

    <p style="margin-top:20px;">
        <a href="{{ url('/login') }}"
           style="background:#4e73df;color:#fff;padding:10px 24px;border-radius:4px;text-decoration:none;font-weight:bold;">
            Login to Your Dashboard
        </a>
    </p>
</td>
@endsection

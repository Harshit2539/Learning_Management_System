@extends('web.default.layouts.email')

@section('body')
    <td valign="top" class="bodyContent" mc:edit="body_content">
        <h1 class="h1">Welcome to Bradford LMS</h1>

        <p>Hi <strong>{{ $studentName }}</strong>,</p>

        <p>Your account has been successfully created. You can now log in using the details below:</p>

        <table style="margin:16px 0;border-collapse:collapse;">
            <tr>
                <td style="padding:6px 12px 6px 0;font-weight:bold;color:#555;">Login:</td>
                <td style="padding:6px 0;">{{ $loginIdentifier }}</td>
            </tr>
            @if(!empty($plainPassword))
            <tr>
                <td style="padding:6px 12px 6px 0;font-weight:bold;color:#555;">Password:</td>
                <td style="padding:6px 0;">{{ $plainPassword }}</td>
            </tr>
            @endif
        </table>

        @if(empty($plainPassword))
            <p>Use the password you set during registration to log in.</p>
        @endif

        <p style="margin-top:20px;">
            <a href="{{ url('/login') }}"
               style="background:#4e73df;color:#fff;padding:10px 24px;border-radius:4px;text-decoration:none;font-weight:bold;">
                Login to Your Account
            </a>
        </p>

        <p style="margin-top:20px;color:#888;font-size:13px;">{{ trans('notification.email_ignore_msg') }}</p>
    </td>
@endsection

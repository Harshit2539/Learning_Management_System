@extends('web.default.layouts.email')

@section('body')
    <td valign="top" class="bodyContent" mc:edit="body_content">
        <h1 class="h1">Course Assigned</h1>
        <p>Hi <strong>{{ $studentName }}</strong>,</p>
        <p>You have been enrolled in a new course:</p>
        <p><strong>{{ $courseTitle }}</strong></p>
        <p>You can access your course by logging into your account and visiting <strong>My Purchases</strong>.</p>
        <p style="margin-top:20px;">
            <a href="{{ url('/course/' . $courseSlug) }}"
               style="background:#4e73df;color:#fff;padding:10px 24px;border-radius:4px;text-decoration:none;font-weight:bold;">
                View Course
            </a>
        </p>
        <p style="margin-top:20px;">{{ trans('notification.email_ignore_msg') }}</p>
    </td>
@endsection

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome — LMS SaaS</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body { background: linear-gradient(135deg,#667eea 0%,#764ba2 100%); min-height:100vh; display:flex; align-items:center; justify-content:center; font-family:'Segoe UI',sans-serif; }
        .card { border-radius:20px; border:none; box-shadow:0 20px 60px rgba(0,0,0,.2); max-width:520px; width:100%; padding:48px 40px; text-align:center; }
        .icon-wrap { width:80px; height:80px; background:#d1fae5; border-radius:50%; display:flex; align-items:center; justify-content:center; margin:0 auto 24px; font-size:2.5rem; }
        h2 { font-weight:800; color:#1e293b; }
        .info-box { background:#f8fafc; border:1px solid #e2e8f0; border-radius:10px; padding:16px 20px; text-align:left; }
        .info-box dt { color:#64748b; font-size:.8rem; font-weight:600; text-transform:uppercase; }
        .info-box dd { color:#1e293b; font-weight:600; margin-bottom:8px; }
        .btn-go { background:linear-gradient(135deg,#667eea,#764ba2); border:none; color:#fff; font-weight:700; padding:14px 32px; border-radius:10px; font-size:1rem; }
        .btn-go:hover { opacity:.9; color:#fff; }
    </style>
</head>
<body>
<div class="card">
    <div class="icon-wrap">🎉</div>
    <h2>You're all set!</h2>
    <p class="text-muted mt-2 mb-4">
        Your organization <strong>{{ $org->name }}</strong> has been created and is now active.
    </p>

    <div class="info-box mb-4">
        <dl class="mb-0">
            <dt>Organization</dt>
            <dd>{{ $org->name }}</dd>
            <dt>Login URL</dt>
            <dd><a href="{{ url('/login') }}">{{ url('/login') }}</a></dd>
            <dt>Your Email</dt>
            <dd>{{ $org->owner->email ?? 'Check your inbox' }}</dd>
        </dl>
    </div>

    <p class="text-muted small mb-4">
        Use the email and password you registered with to log in.
        You will land on your organization's admin dashboard.
    </p>

    <a href="{{ url('/login') }}" class="btn btn-go w-100">
        Go to Login →
    </a>
</div>
</body>
</html>

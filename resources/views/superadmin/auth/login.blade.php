@extends('admin.auth.auth_layout')

@section('content')
    <div class="p-4 m-3">
        <img src="{{ getGeneralSettings()['logo'] ?? '' }}" alt="logo" width="40%" class="mb-5 mt-2">

        <h4 class="text-dark font-weight-normal">Welcome to <span class="font-weight-bold">Super Admin</span></h4>
        <p class="text-muted">Sign in to manage organizations and plans.</p>

        @if($errors->any())
            <div class="alert alert-danger py-2">{{ $errors->first() }}</div>
        @endif

        <form method="POST" action="{{ route('superadmin.login') }}" class="needs-validation" novalidate>
            @csrf
            <div class="form-group">
                <label for="email">Email</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}"
                       class="form-control @error('email') is-invalid @enderror"
                       tabindex="1" required autofocus>
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input id="password" type="password" name="password"
                       class="form-control @error('password') is-invalid @enderror"
                       tabindex="2" required>
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-primary btn-lg btn-block" tabindex="3">
                    Login
                </button>
            </div>
        </form>

        <div class="mt-3 text-center">
            <a href="/login" class="text-muted" style="font-size:13px;">← Back to main site</a>
        </div>
    </div>
@endsection

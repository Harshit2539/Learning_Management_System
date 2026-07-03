@extends('superadmin.layouts.app')
@section('title', 'Create Plan')
@section('page-title', 'Create Plan')

@section('content')
<div class="page-header">
    <h1>Create Plan</h1>
    <p>Define a new subscription plan for organizations.</p>
</div>

<div class="row justify-content-center">
<div class="col-lg-8">
<div class="sa-card p-4">
    @if($errors->any())
        <div class="sa-alert error"><i class="bi bi-exclamation-circle-fill"></i> {{ $errors->first() }}</div>
    @endif
    <form action="{{ route('superadmin.plans.store') }}" method="POST">
        @csrf
        @include('superadmin.plans._form', ['plan' => null])
        <div style="display:flex;gap:10px;margin-top:24px;padding-top:20px;border-top:1px solid var(--border);">
            <button type="submit" class="btn-sa btn-sa-primary"><i class="bi bi-check-lg"></i> Create Plan</button>
            <a href="{{ route('superadmin.plans.index') }}" class="btn-sa btn-sa-outline">Cancel</a>
        </div>
    </form>
</div>
</div>
</div>
@endsection

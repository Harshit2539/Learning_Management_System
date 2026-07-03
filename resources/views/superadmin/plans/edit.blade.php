@extends('superadmin.layouts.app')
@section('title', 'Edit Plan')
@section('page-title', 'Edit Plan')

@section('content')
<div class="page-header">
    <h1>Edit Plan — {{ $plan->name }}</h1>
    <p>Update this subscription plan's details and limits.</p>
</div>

<div class="row justify-content-center">
<div class="col-lg-8">
<div class="sa-card p-4">
    @if($errors->any())
        <div class="sa-alert error"><i class="bi bi-exclamation-circle-fill"></i> {{ $errors->first() }}</div>
    @endif
    <form action="{{ route('superadmin.plans.update', $plan->id) }}" method="POST">
        @csrf
        @include('superadmin.plans._form', ['plan' => $plan])
        <div style="display:flex;gap:10px;margin-top:24px;padding-top:20px;border-top:1px solid var(--border);">
            <button type="submit" class="btn-sa btn-sa-primary"><i class="bi bi-check-lg"></i> Save Changes</button>
            <a href="{{ route('superadmin.plans.index') }}" class="btn-sa btn-sa-outline">Cancel</a>
        </div>
    </form>
</div>
</div>
</div>
@endsection

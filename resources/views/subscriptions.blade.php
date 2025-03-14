@extends('layouts.app')

@section('content')
<div class="subscriptions-container">
    <h2>Choose Your Plan</h2>
    <div class="plans">
        <div class="plan">
            <h3>Free Plan</h3>
            <p>Limited Access</p>
            <a href="{{ route('subscribe', 'free') }}" class="btn btn-secondary">Choose Free</a>
        </div>
        <div class="plan">
            <h3>Premium Plan</h3>
            <p>Full Access</p>
            <a href="{{ route('subscribe', 'premium') }}" class="btn btn-primary">Choose Premium</a>
        </div>
    </div>
</div>
@endsection
@extends('layouts.auth')

@section('content')
    <div class="card border-0 rounded-lg">
        <div class="card-header bg-dark border-0 rounded-top-lg">
            <h5 class="text-white">{{ __('Verify Your Email Address') }}</h5>
        </div>

        <div class="card-body">
            @if (session('resent'))
                <div class="alert alert-success" role="alert">
                    {{ __('A fresh verification link has been sent to your email address.') }}
                </div>
            @endif

            {{ __('Before proceeding, please check your email for a verification link.') }}
            {{ __('If you did not receive the email') }},
            <form class="d-inline" method="POST" action="{{ route('verification.resend') }}">
                @csrf
                <button type="submit" class="btn btn-link p-0 m-0 align-baseline">{{ __('click here to request another') }}</button>.
            </form>

            <div class="form-group row mb-0">
                <div class="col-md-6 offset-md-4">
                    <a href="{{ route('home') }}" class="btn btn-primary">
                        {{ __('Verified') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection

@extends('layouts.app')
@section('content')
    <div class="card">
        <h3 class="card-title text-center mt-3">Register</h3>
        <!-- Validation Errors -->
        <form method="POST" action="{{ route('register') }}" class="card-body">
            @csrf
            <div>
                <input id="name" class="form-control" type="text" name="name" required
                         autofocus placeholder="Name"/>
            </div>
            <div class="mt-3">
                <input id="email" class="form-control mt-1 w-full" type="email" name="email"  required
                       placeholder="Email"/>
            </div>
            <div class="mt-3">
                <input id="password" class="form-control mt-1 w-full"
                                type="password"
                                name="password"
                                required autocomplete="new-password" placeholder="Password"/>
            </div>
            <div class="mt-3">
                <input id="password_confirmation" class="form-control mt-1 w-full"
                                type="password"
                                name="password_confirmation" required placeholder="Confirm Password"/>
            </div>

            <div class="flex items-center justify-end mt-4">
                <button type="submit" class="btn btn-primary ml-4">
                    {{ __('Register') }}
                </button>
            </div>
        </form>
    </div>
@endsection

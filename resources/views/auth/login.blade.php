@extends('layouts.app')
@section('content')
        <form method="POST" action="{{ route('login') }}" class="card">
            @csrf
            <div class="card-body">
                <div class="form-group">
                <!-- Email Address -->
                    <input id="email" class="form-control my-3" type="email" name="email" required
                           autofocus placeholder="Email"/>

                    <!-- Password -->
                    <input id="password" class="form-control my-3"
                                    type="password"
                                    name="password"
                                    required autocomplete="current-password"
                                    placeholder="Password"/>
                </div>
                <!-- Remember Me -->
                <div class="block mt-4">
                    <label for="remember_me" class="inline-flex items-center">
                        <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" name="remember">
                        <span class="ml-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
                    </label>
                </div>

                <div class="flex items-center justify-end mt-4">
                    @if (Route::has('password.request'))
                        <a class="underline text-sm text-gray-600 hover:text-gray-900" href="{{ route('password.request') }}">
                            {{ __('Forgot your password?') }}
                        </a>
                    @endif

                    <button class="btn btn-primary" type="submit">
                        Login
                    </button>
                </div>
            </div>


        </form>
@endsection

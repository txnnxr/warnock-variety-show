<x-guest-layout>
    <x-auth-card>
        <h1 class="card-heading">Log In</h1>

        <x-auth-session-status :status="session('status')" />
        <x-auth-validation-errors :errors="$errors" />

        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="mb-3">
                <x-input-label for="email" :value="__('Email')" />
                <x-text-input id="email" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            </div>

            <div class="mb-3">
                <x-input-label for="password" :value="__('Password')" />
                <x-text-input id="password" type="password" name="password" required autocomplete="current-password" />
            </div>

            <div class="form-check mb-4">
                <input id="remember_me" type="checkbox" class="form-check-input" name="remember">
                <label for="remember_me" class="form-check-label">{{ __('Remember me') }}</label>
            </div>

            <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}">{{ __('Forgot your password?') }}</a>
                @endif
                <x-primary-button>{{ __('Log In') }}</x-primary-button>
            </div>
        </form>
    </x-auth-card>
</x-guest-layout>

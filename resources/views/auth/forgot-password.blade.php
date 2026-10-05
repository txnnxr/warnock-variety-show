<x-guest-layout>
    <x-auth-card>
        <h1 class="card-heading">Forgot Password</h1>
        <p>{{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}</p>

        <x-auth-session-status :status="session('status')" />
        <x-auth-validation-errors :errors="$errors" />

        <form method="POST" action="{{ route('password.email') }}">
            @csrf
            <div class="mb-4">
                <x-input-label for="email" :value="__('Email')" />
                <x-text-input id="email" type="email" name="email" :value="old('email')" required autofocus />
            </div>

            <div class="d-grid d-sm-flex justify-content-sm-end">
                <x-primary-button>{{ __('Email Password Reset Link') }}</x-primary-button>
            </div>
        </form>
    </x-auth-card>
</x-guest-layout>

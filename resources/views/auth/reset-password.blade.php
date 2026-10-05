<x-guest-layout>
    <x-auth-card>
        <h1 class="card-heading">Reset Password</h1>

        <x-auth-validation-errors :errors="$errors" />

        <form method="POST" action="{{ route('password.store') }}">
            @csrf
            <input type="hidden" name="token" value="{{ $request->route('token') }}">

            <div class="mb-3">
                <x-input-label for="email" :value="__('Email')" />
                <x-text-input id="email" type="email" name="email" :value="old('email', $request->email)" required autofocus autocomplete="username" />
            </div>

            <div class="mb-3">
                <x-input-label for="password" :value="__('Password')" />
                <x-text-input id="password" type="password" name="password" required autocomplete="new-password" />
            </div>

            <div class="mb-4">
                <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
                <x-text-input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password" />
            </div>

            <div class="d-grid d-sm-flex justify-content-sm-end">
                <x-primary-button>{{ __('Reset Password') }}</x-primary-button>
            </div>
        </form>
    </x-auth-card>
</x-guest-layout>

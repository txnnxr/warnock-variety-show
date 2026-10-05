<x-guest-layout>
    <x-auth-card>
        <h1 class="card-heading">Confirm Password</h1>
        <p>{{ __('This is a secure area of the application. Please confirm your password before continuing.') }}</p>

        <x-auth-validation-errors :errors="$errors" />

        <form method="POST" action="{{ route('password.confirm') }}">
            @csrf
            <div class="mb-4">
                <x-input-label for="password" :value="__('Password')" />
                <x-text-input id="password" type="password" name="password" required autocomplete="current-password" />
            </div>

            <div class="d-grid d-sm-flex justify-content-sm-end">
                <x-primary-button>{{ __('Confirm') }}</x-primary-button>
            </div>
        </form>
    </x-auth-card>
</x-guest-layout>

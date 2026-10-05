<x-guest-layout>
    <x-auth-card>
        <h1 class="card-heading">Verify Your Email</h1>
        <p>{{ __('Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn\'t receive the email, we will gladly send you another.') }}</p>

        @if (session('status') == 'verification-link-sent')
            <div class="alert alert-success">
                {{ __('A new verification link has been sent to the email address you provided during registration.') }}
            </div>
        @endif

        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                <x-primary-button>{{ __('Resend Verification Email') }}</x-primary-button>
            </form>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn btn-link">{{ __('Log Out') }}</button>
            </form>
        </div>
    </x-auth-card>
</x-guest-layout>

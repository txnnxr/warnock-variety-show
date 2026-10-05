<section>
    <header class="mb-3">
        <h2 class="section-heading">{{ __('Delete Account') }}</h2>
        <p class="mb-0">{{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.') }}</p>
    </header>

    <x-danger-button
        type="button"
        x-data=""
        x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
    >{{ __('Delete Account') }}</x-danger-button>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="card-body">
            @csrf
            @method('delete')

            <h2 class="h4">{{ __('Are you sure you want to delete your account?') }}</h2>

            <p>{{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.') }}</p>

            <div class="mb-3">
                <x-input-label for="password" value="{{ __('Password') }}" class="visually-hidden" />
                <x-text-input id="password" name="password" type="password" placeholder="{{ __('Password') }}" />
                <x-input-error :messages="$errors->userDeletion->get('password')" />
            </div>

            <div class="d-flex justify-content-end gap-2">
                <x-secondary-button x-on:click="$dispatch('close')">{{ __('Cancel') }}</x-secondary-button>
                <x-danger-button>{{ __('Delete Account') }}</x-danger-button>
            </div>
        </form>
    </x-modal>
</section>

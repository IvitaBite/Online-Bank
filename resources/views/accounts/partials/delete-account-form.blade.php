<section class="space-y-6">
    <header>
        <h2 class="text-lg font-medium text-yellow-950">
            {{ __('Delete Account') }}
        </h2>

        <p class="mt-1 text-sm text-yellow-900">
            {{ __('Deleting the selected account will result in the permanent removal of all associated data. Prior to deleting the bank account, we recommend downloading any data or information you wish to keep. Please note that only accounts with a balance of 0.00 and no active investments or savings can be deleted.') }}
        </p>
    </header>

    <x-danger-button
        x-data=""
        x-on:click.prevent="$dispatch('open-modal', 'confirm-account-deletion')"
    >{{ __('Delete Account') }}</x-danger-button>

    <x-modal name="confirm-account-deletion" :show="$errors->accountDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('accounts.destroy', $account->account_name) }}" class="p-6 bg-custom">
            @csrf
            @method('delete')

            <h2 class="text-lg font-medium text-yellow-950">
                {{ __('Are you sure you want to delete your account?') }}
            </h2>

            <p class="mt-1 text-sm text-yellow-900">
                {{ __('Once the bank account is deleted, all of its data will be permanently deleted. Please enter your password to confirm you would like to permanently delete the bank account.') }}
            </p>

            <div class="mt-6">
                <x-input-label for="password" value="{{ __('Password') }}" class="sr-only" />

                <x-text-input
                    id="password"
                    name="password"
                    type="password"
                    class="mt-1 block w-3/4"
                    placeholder="{{ __('Password') }}"
                />

                <x-input-error :messages="$errors->accountDeletion->get('password')" class="mt-2" />
            </div>

            <div class="mt-6 flex justify-end">
                <x-secondary-button x-on:click="$dispatch('close')" class="button">
                    {{ __('Cancel') }}
                </x-secondary-button>

                <x-danger-button class="ms-3">
                    {{ __('Delete Account') }}
                </x-danger-button>
            </div>
        </form>
    </x-modal>
</section>

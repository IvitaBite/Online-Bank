<section class="space-y-6">
    <header>
        <h2 class="text-lg font-medium text-yellow-950">
            {{ $account->status == 'active'
                ? __('Block Account')
                : __('Unblock Account')
            }}
        </h2>

        <p class="mt-1 text-sm text-yellow-900">
            {{ $account->status == 'active'
                ? __('Blocking the selected account will prevent any transactions until it is unblocked.')
                : __('Unblocking the selected account will allow transactions to be executed again.')
            }}
        </p>
    </header>

    <x-danger-button
            x-data=""
            x-on:click.prevent="$dispatch('open-modal', 'confirm-account-status-change')"
    >
        {{ $account->status == 'active' ? __('Block Account') : __('Unblock Account') }}
    </x-danger-button>

    <x-modal name="confirm-account-status-change" :show="$errors->accountBlock->isNotEmpty()" focusable>
        <form method="POST" action="{{ route('accounts.block', $account->account_name) }}" class="p-6 bg-custom">
            @csrf
            @method('PATCH')

            <h2 class="text-lg font-medium text-yellow-950">
                {{ $account->status == 'active'
                    ? __('Are you sure you want to block your account?')
                    : __('Are you sure you want to unblock your account?')
                }}
            </h2>

            <p class="mt-1 text-sm text-yellow-900">
                {{ $account->status == 'active'
                    ? __('If you block your account, you will not be able to use it until you unblock it.')
                    : __('If you unblock your account, you will be able to use it again.')
                }}
                {{ $account->status == 'active'
                    ? __('Please enter your password to confirm you would like to block your account.')
                    : __('Please enter your password to confirm you would like to unblock your account.')
                }}
            </p>

            <div class="mt-6">
                <x-input-label for="password" value="{{ __('Password') }}" class="sr-only"/>

                <x-text-input
                        id="password"
                        name="password"
                        type="password"
                        class="mt-1 block w-3/4"
                        placeholder="{{ __('Password') }}"
                />

                <x-input-error :messages="$errors->accountBlock->get('password')" class="mt-2"/>
            </div>

            <div class="mt-6 flex justify-end">
                <x-secondary-button x-on:click="$dispatch('close')" class="button">
                    {{ __('Cancel') }}
                </x-secondary-button>

                <x-danger-button class="ms-3">
                    {{ __('Block Account') }}
                </x-danger-button>
            </div>
        </form>
    </x-modal>
</section>

<section class="space-y-6">
    <header>
        <h2 class="text-lg font-medium text-yellow-950">
            {{ __('Edit Account Name') }}
        </h2>
    </header>
    <form action="{{ route('accounts.update', $account->account_name) }}" method="POST">
        @csrf
        @method('PUT')
        <div>
            <x-text-input id="account_name" class="block mt-2 w-full" type="text" name="account_name" :value="old('account_name', $account->account_name)"
                          class="input-field"
                          required autofocus autocomplete="account_name"/>
            <x-input-error :messages="$errors->get('account_name')" class="mt-2" />
        </div>
        <div class="mt-4">
            <x-primary-button class="button">
                {{ __('Save') }}
            </x-primary-button>
        </div>
    </form>
</section>

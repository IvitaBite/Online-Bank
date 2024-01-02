<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-yellow-950 leading-tight">
                {{ __('Edit ' . $account->account_type . ' Account ' . $account->account_name) }}
            </h2>
        </div>
    </x-slot>

    <div class="py-2">
        <div class="w-full sm:max-w-md mt-6 mx-auto px-6 py-4">
            <div class="bg-custom-color overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-yellow-950">
                    <form action="{{ route('accounts.update', $account->account_name) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div>
                            <x-input-label for="account_name" :value="__('Account Name')" class="text-yellow-950 custom-label"/>
                            <x-text-input id="account_name" class="block mt-2 w-full" type="text" name="account_name" :value="old('account_name')"
                                          class="input-field"
                                          placeholder="e.g. {{ Auth::user()->firstname }} {{ Auth::user()->lastname }}"
                                          required autofocus autocomplete="account_name"/>
                            <x-input-error :messages="$errors->get('account_name')" class="mt-2" />
                        </div>
                        <div class="flex items-center justify-end mt-4">
                            <x-primary-button class="ms-4 button">
                                {{ __('Save') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="py-2">
        <div class="w-full sm:max-w-md mt-6 mx-auto px-6 py-4">
            <div class="bg-custom-color overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-yellow-950">
                    @include('accounts.partials.delete-account-form')
                </div>
            </div>
        </div>
    </div>

</x-app-layout>

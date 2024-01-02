<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-yellow-950 leading-tight">
                {{ __('Create New Accounts') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="w-full sm:max-w-md mt-6 mx-auto px-6 py-4">
            <div class="bg-custom-color overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-yellow-950">
                    <form action="{{ route('accounts.store') }}" method="POST">
                        @csrf

                        <div>
                            <x-input-label for="account_name" :value="__('Account Name')" class="text-yellow-950 custom-label"/>
                            <x-text-input id="account_name" class="block mt-2 w-full" type="text" name="account_name" :value="old('account_name')"
                                          class="input-field"
                                          placeholder="e.g. {{ Auth::user()->firstname }} {{ Auth::user()->lastname }}"
                                          required autofocus autocomplete="account_name"/>
                            <x-input-error :messages="$errors->get('account_name')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="type" :value="__('Account Type')" class="mt-4 text-yellow-950 custom-label"/>
                            <select name="type" id="type" class="mt-2 rounded-md"
                                    required autofocus autocomplete="type">
                                <option value="checking">Checking</option>
                                <option value="savings">Savings</option>
                                <option value="investment">Investment</option>
                            </select>
                        </div>

                        <div class="mt-4">
                            <x-input-label for="currency_symbol" :value="__('Currency')" class="mt-4 text-yellow-950 custom-label"/>
                            <select name="currency_symbol" id="currency_symbol" class="mt-2 rounded-md"
                                    required autofocus autocomplete="currency_symbol">
                                @foreach($validCurrencies as $currency)
                                    <option value="{{ $currency->symbol }}">
                                        {{ $currency->symbol . ' - ' . $currency->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="flex items-center justify-end mt-4">
                            <x-primary-button class="ms-4 button">
                                {{ __('Create Account') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>



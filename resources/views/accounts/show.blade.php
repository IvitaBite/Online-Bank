<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-yellow-950 leading-tight">
                {{ __($account->account_name) }}
                <p>Account Number: {{ $account->account_number }}</p>
                <p>Account Name: {{ $account->account_name }}</p>
                <p>Balance: {{ number_format(($account->balance / 1000), 2) }}</p>
                <p>Currency: {{ $account->currency->name }} ({{ $account->currency->symbol }})</p>
            </h2>
            <a href="{{ route('accounts.edit', $account->account_name) }}">
                <x-primary-button class="ms-4 button">
                    {{ __('Edit Account') }}
                </x-primary-button>
            </a>
        </div>
    </x-slot>
    <div class="py-12">
        <div class="w-full sm:max-w-md mt-6 mx-auto px-6 py-4">
            <div class="bg-custom-color overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-yellow-950">

                    <div class="p-4 sm:p-8 bg-custom-color shadow sm:rounded-lg">
                        <div class="max-w-xl">

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>

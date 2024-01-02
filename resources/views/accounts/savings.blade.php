<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-yellow-950 leading-tight">
                {{ __('Savings Accounts') }}
            </h2>
            <div class="text-right">
                <a href="{{ route('accounts.create') }}" class="text-yellow-900 hover:text-yellow-950">Create New Account</a>
            </div>
        </div>
    </x-slot>
    @foreach($accounts as $account)
        <div class="py-2">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-custom-color overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <a href="{{ route('accounts.show', $account->account_name) }}">{{ $account->account_name }}</a>
                        <div class="flex items-center justify-end mt-4">
                            <a href="{{ route('accounts.edit', $account->account_name) }}">
                                <x-primary-button class="ms-4 button">
                                    {{ __('Edit') }}
                                </x-primary-button>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</x-app-layout>

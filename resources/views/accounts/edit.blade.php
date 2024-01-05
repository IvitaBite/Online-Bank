<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-yellow-950 leading-tight">
                {{ __('Edit ' . ucfirst($account->type) . ' Account: ' . $account->account_name) }}
            </h2>
        </div>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-custom-color shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('accounts.partials.edit-account-name-form')
                </div>
            </div>
            <div class="p-4 sm:p-8 bg-custom-color shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('accounts.partials.block-account-form')
                </div>
            </div>
            <div class="p-4 sm:p-8 bg-custom-color shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('accounts.partials.delete-account-form')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

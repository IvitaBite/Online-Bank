<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-yellow-950 leading-tight">
                {{ __('Welcome, ' . Auth::user()->firstname . ' ' . Auth::user()->lastname) }}
            </h2>
            <div class="text-right">
                <a href="{{ route('accounts.create') }}" class="text-yellow-900 hover:text-yellow-950">Create New
                    Account</a>
            </div>
        </div>
    </x-slot>
    @foreach($accounts as $type => $accountGroup)
        <div class="py-4">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-custom-color overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <a href="{{ route('accounts',$type) }}">
                            <h2 class="text-yellow-950 bold-and-large">{{ ucfirst($type) . ' Accounts'}}</h2>
                        </a>
                        <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                            <table class="w-full text-sm text-left rtl:text-right text-yellow-950 mt-2">
                                <thead class="bold-and-large text-xs text-yellow-950 uppercase bg-yellow-200/25">
                                <tr>
                                    <th scope="col" class="px-6 py-3">Name</th>
                                    <th scope="col" class="px-6 py-3">Number</th>
                                    <th scope="col" class="px-6 py-3">Balance</th>
                                    <th scope="col" class="px-6 py-3">Currency</th>
                                    <th scope="col" class="px-6 py-3">Status</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($accountGroup as $account)
                                    <tr class="bg-white border-b large">
                                        <td class="px-6 py-4">
                                            <a href="{{ route('accounts.show', $account->account_name) }}">
                                                {{ $account->account_name }}
                                            </a>
                                        </td>
                                        <td class="px-6 py-4">{{ $account->account_number }}</td>
                                        <td class="px-6 py-4">{{ number_format(($account->balance / 1000), 2) }}</td>
                                        <td class="px-6 py-4">{{ $account->currency_symbol }}</td>
                                        <td class="px-6 py-4">{{ ucfirst($account->status) }}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
    @endforeach
</x-app-layout>

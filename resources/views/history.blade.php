<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-yellow-950 leading-tight">
                {{ __('History') }}
            </h2>
        </div>
    </x-slot>
    <div class="py-2">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-custom-color overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-yellow-950">
                    @if ($transactions->isEmpty())
                        <p>No transactions found.</p>
                    @else
                        <table class="w-full text-sm text-left rtl:text-right text-yellow-950">
                            <thead class="bold-and-large text-xs text-yellow-950 uppercase bg-yellow-200/25">
                            <tr>
                                <th scope="col" class="px-6 py-3">Transfer Date</th>
                                <th scope="col" class="px-6 py-3">From Account</th>
                                <th scope="col" class="px-6 py-3">To Account</th>
                                <th scope="col" class="px-6 py-3">Amount</th>
                                <th scope="col" class="px-6 py-3">Description</th>
                                <th scope="col" class="px-6 py-3">Type</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($transactions as $transaction)
                                <tr class="bg-white border-b large">
                                    <td class="px-6 py-4">{{ $transaction->created_at }}</td>
                                    <td class="px-6 py-4">{{$transaction->account_number_from}}
                                        <br><small>{{ $transaction->fromAccount->account_name }}</small>
                                    </td>
                                    <td class="px-6 py-4">{{ $transaction->account_number_to }}
                                        <br><small>{{ $transaction->toAccount->account_name }}</small>
                                    </td>
                                    <td class="px-6 py-4">
                                        {{  number_format(($transaction->amount / 1000), 2) . " " . $transaction->currency_symbol_to}}
                                    </td>
                                    <td class="px-6 py-4">{{ $transaction->description }}</td>
                                    <td class="px-6 py-4">{{ $transaction->type }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <div class="py-2">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-custom-color overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-yellow-950">
                    @if ($investments->isEmpty())
                        <p>No investments found.</p>
                    @else
                        <table class="w-full text-sm text-left rtl:text-right text-yellow-950">
                            <thead class="bold-and-large text-xs text-yellow-950 uppercase bg-yellow-200/25">
                            <tr>
                                <th scope="col" class="px-6 py-3">Investment Date</th>
                                <th scope="col" class="px-6 py-3">Investment Account</th>
                                <th scope="col" class="px-6 py-3">Type</th>
                                <th scope="col" class="px-6 py-3">Symbol</th>
                                <th scope="col" class="px-6 py-3">Amount</th>
                                <th scope="col" class="px-6 py-3">Buy Price</th>
                                <th scope="col" class="px-6 py-3">Sell Price</th>
                                <th scope="col" class="px-6 py-3">Status</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($investments as $investment)
                                <tr class="bg-white border-b large">
                                    <td class="px-6 py-4">{{ $investment->created_at }}</td>
                                    <td class="px-6 py-4">{{$investment->account_number}}</td>
                                    <td class="px-6 py-4">{{ $investment->type }}</td>
                                    <td class="px-6 py-4">{{ $investment->symbol }}</td>
                                    <td class="px-6 py-4">{{ $investment->amount }}</td>
                                    <td class="px-6 py-4">
                                        {{  number_format(($investment->buy_rate), 4)}}
                                    </td>
                                    <td class="px-6 py-4">
                                        {{  number_format(($investment->sell_rate), 4)}}
                                    </td>
                                    <td class="px-6 py-4">{{ $investment->status }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

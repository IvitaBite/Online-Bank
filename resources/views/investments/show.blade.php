<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-yellow-950 leading-tight">
                {{ __($account->account_name) }}
                <p>Account Number: {{ $account->account_number }}</p>
                <p id="accountForm">Account Name: {{ $account->account_name }}</p>
                <p>Balance: {{ number_format(($account->balance / 1000), 2) }}</p>
                <p>Currency: {{ $account->currency->name }} ({{ $account->currency->symbol }})</p>
            </h2>
            <a href="{{ route('accounts.edit', $account->account_name) }}">
                <x-primary-button class="ms-4 button">
                    {{ __('Edit Account') }}
                </x-primary-button>
            </a>
        </div>
        @if ($account->type == 'investment')
            <div class="flex items-center justify-end mt-4">
                <a href="{{ route('investments.index') }}">
                    <x-primary-button class="ms-4 button">
                        {{ __('Buy Crypto') }}
                    </x-primary-button>
                </a>
            </div>
        @endif
    </x-slot>
    <div class="py-2">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-custom-color overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-yellow-950">
                    <form action="{{ route('investments.sell', $account->account_name) }}" method="POST" id="sellForm">
                        @csrf

                        <input type="hidden" name="account_name" id="accountField" value="">
                        <input type="hidden" name="symbol" id="symbolField" value="">
                        <input type="hidden" name="amount" id="amountField" value="">

                        <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                            <table class="w-full text-sm text-left rtl:text-right text-yellow-950">
                                <thead class="bold-and-large text-xs text-yellow-950 uppercase bg-yellow-200/25">
                                <tr>
                                    <th scope="col" class="px-6 py-3">Symbol</th>
                                    <th scope="col" class="px-6 py-3">Name</th>
                                    <th scope="col" class="px-6 py-3">Amount</th>
                                    <th scope="col" class="px-6 py-3">Purchase Value</th>
                                    <th scope="col" class="px-6 py-3">Value</th>
                                    <th scope="col" class="px-6 py-3">Value change %</th>
                                    <th scope="col" class="px-6 py-3">Sell Amount</th>
                                    <th scope="col" class="px-6 py-3"></th>
                                </tr>
                                </thead>
                                <tbody>
                                @if($investments->isNotEmpty())
                                    @foreach($investments->where('status', 'active') as $index => $investment)
                                        <tr class="bg-white border-b large">
                                            <td class="px-6 py-4">{{ $investment->symbol }}</td>
                                            <td class="px-6 py-4">{{ $investment->cryptocurrency->name }}</td>
                                            <td class="px-6 py-4">{{ $investment->amount }}</td>
                                            <td id="purchaseValue_{{ $index }}" class="px-6 py-4">
                                                {{ number_format(($investment->amount * $investment->buy_rate), 2) }}
                                            </td>
                                            <td id="value_{{ $index }}" class="px-6 py-4">
                                                {{ number_format(($investment->amount * $investment->cryptocurrency->sell_rate), 2) }}
                                            </td>
                                            <td id="valueChange_{{ $index }}" class="px-6 py-4">
                                                {{ number_format((((($investment->amount * $investment->cryptocurrency->sell_rate) - ($investment->amount * $investment->buy_rate)) / ($investment->amount * $investment->buy_rate)) * 100), 2) }}%
                                            </td>
                                            <td class="px-6 py-4">
                                                <div class="flex items-center">
                                                    <input id="amount_{{ $index }}" class="mt-2 rounded-md w-32"
                                                           type="number"
                                                           name="amount"
                                                           value="{{ old('amount') }}"
                                                           min="0"
                                                           step="0.001"
                                                           placeholder="e.g. {{ $investment->amount }}"
                                                           required autofocus autocomplete="amount_{{ $index }}"/>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4">
                                                <div class="flex items-center justify-end">
                                                    <x-primary-button class="button"
                                                                      onclick="sellOne({{ $index }}, '{{ $investment->symbol }}', '{{ $account->account_name }}')">
                                                        {{ __('Sell') }}
                                                    </x-primary-button>
                                                </div>
                                            </td>
                                            @endforeach
                                        </tr>
                                        @else
                                            <p>No investments found for this account.</p>
                                        @endif
                                </tbody>
                            </table>
                        </div>
                    </form>
                    <script>
                        function updateAccountNumber(form) {
                            var accountField = document.getElementById('accountField');
                            var selectedValue = form.account_name.value;
                            accountField.value = selectedValue;
                            form.submit();
                        }

                        function sellOne(index, symbol, accountNumber) {
                            var amountInput = document.getElementById('amount_' + index);
                            var form = document.getElementById('sellForm');

                            var symbolInput = document.createElement('input');
                            symbolInput.type = 'hidden';
                            symbolInput.name = 'symbol';
                            symbolInput.value = symbol;

                            var amountHiddenInput = document.createElement('input');
                            amountHiddenInput.type = 'hidden';
                            amountHiddenInput.name = 'amount';
                            amountHiddenInput.value = amountInput.value;

                            var accountHiddenInput = document.createElement('input');
                            accountHiddenInput.type = 'hidden';
                            accountHiddenInput.name = 'account_name';
                            accountHiddenInput.value = accountNumber;

                            form.appendChild(symbolInput);
                            form.appendChild(amountHiddenInput);
                            form.appendChild(accountHiddenInput);

                            form.submit();
                        }

                    </script>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>

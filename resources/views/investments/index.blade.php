<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-yellow-950 leading-tight">
                {{ __('Crypto Investments') }}
            </h2>
        </div>
    </x-slot>
    <div class="py-2">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-custom-color overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-yellow-950">
                    <div class="flex items-center">
                        <h2 class="font-semibold text-xl text-yellow-950 leading-tight">
                            {{ __('Choose Your Investment Account') }}
                        </h2>
                        <div class="mx-6">
                            <form action="{{ route('investments.index') }}" method="GET" id="accountForm">
                                <select name="account_number" onchange="updateAccountNumber(this.form)" class="rounded-md">
                                    @foreach($accounts as $account)
                                        <option
                                            value="{{ $account->account_number }}" {{ optional($selectedAccount)->account_number == $account->account_number ? 'selected' : '' }}>
                                            {{ $account->account_name . ' - ' . $account->account_number}}
                                            ({{ number_format($account->balance / 1000, 2) }} {{ $account->currency_symbol }}
                                            )
                                        </option>
                                    @endforeach
                                </select>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="py-2">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-custom-color overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-yellow-950">
                    <form action="{{ route('investments.buy') }}" method="POST" id="buyForm">
                        @csrf

                        <input type="hidden" name="account_number" id="accountField" value="">
                        <input type="hidden" name="symbol" id="symbolField" value="">
                        <input type="hidden" name="amount" id="amountField" value="">

                    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                        <table class="w-full text-sm text-left rtl:text-right text-yellow-950">
                            <thead class="bold-and-large text-xs text-yellow-950 uppercase bg-yellow-200/25">
                            <tr>
                                <th scope="col" class="px-6 py-3">Symbol</th>
                                <th scope="col" class="px-6 py-3">Name</th>
                                <th scope="col" class="px-6 py-3">Pair</th>
                                <th scope="col" class="px-6 py-3">Buy Rate</th>
                                <th scope="col" class="px-6 py-3">Sell Rate</th>
                                <th scope="col" class="px-6 py-3">Amount</th>
                                <th scope="col" class="px-6 py-3">Price</th>
                                <th scope="col" class="px-6 py-3"></th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($cryptocurrencies as $index =>$crypto)
                                <tr class="bg-white border-b large">
                                    <td id="symbol" scope="row" class="px-6 py-4 bold text-yellow-950 whitespace-nowrap">
                                        {{ $crypto->symbol }}
                                    </td>
                                    <td class="px-6 py-4">{{ $crypto->name }}</td>
                                    <td class="px-6 py-4">{{ $crypto->pair }}</td>
                                    <td class="px-6 py-4">{{ number_format($crypto->buy_rate, 4) }}</td>
                                    <td class="px-6 py-4">{{ number_format($crypto->buy_rate, 4) }}</td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center">
                                            <input id="amount_{{ $index }}" class="mt-2 rounded-md w-32" type="number"
                                                   name="amount"
                                                   value="{{ old('amount') }}"
                                                   min="0"
                                                   step="0.001"
                                                   placeholder="e.g. 0.001"
                                                   required autofocus autocomplete="amount_{{ $index }}"/>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span id="total_{{ $index }}">0.00</span> {{ $crypto->currency_symbol }}
                                    </td>

                                    <script>
                                        var amountInput{{ $index }} = document.getElementById('amount_{{ $index }}');
                                        var totalSpan{{ $index }}= document.getElementById('total_{{ $index }}');
                                        var buyRate{{ $index }} = {{ $crypto->buy_rate }};

                                        function updateTotal{{ $index }}() {
                                            var total = (amountInput{{ $index }}.value * buyRate{{ $index }}).toFixed(2);
                                            totalSpan{{ $index }}.textContent = total;
                                        }

                                        amountInput{{ $index }}.addEventListener('input', updateTotal{{ $index }});
                                    </script>

                                    <td class="px-6 py-4">
                                        <div class="flex items-center justify-end">
                                            <x-primary-button class="button"
                                                              onclick="buyOne({{ $index }}, '{{ $crypto->symbol }}', '{{ $account->account_number }}')">
                                                {{ __('Buy') }}
                                            </x-primary-button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    </form>

                    <script>
                        function updateAccountNumber(form) {
                            var accountField = document.getElementById('accountField');
                            var selectedValue = form.account_number.value;
                            accountField.value = selectedValue;
                            form.submit();
                        }

                        function buyOne(index, symbol, accountNumber) {
                            var amountInput = document.getElementById('amount_' + index);
                            var form = document.getElementById('buyForm');

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
                            accountHiddenInput.name = 'account_number';
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


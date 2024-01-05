<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-yellow-950 leading-tight">
                {{ __('Make New Transaction') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-2">
        <div class="w-full sm:max-w-md mt-6 mx-auto px-6 py-4">
            <div class="bg-custom-color overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-yellow-950">
                    <form action="{{ route('transactions.store') }}" method="POST">
                        @csrf
                        <div>
                            <x-input-label for="account_number_from" :value="__('Choose Account')"
                                           class="text-yellow-950 custom-label"/>
                            <select name="account_number_from" id="account_number_from" class="mt-2 rounded-md"
                                    required autofocus autocomplete="account_number_from">
                                @foreach($accounts as $type => $accountsGroup)
                                    <optgroup label="{{ ucfirst($type) }} Accounts">
                                        @foreach($accountsGroup as $account)
                                            <option value="{{ $account->account_number }}"
                                                    data-currency="{{ $account->currency_symbol }}">
                                                {{ $account->account_name . ' - ' . $account->account_number}}</option>
                                        @endforeach
                                    </optgroup>
                                @endforeach
                            </select>
                        </div>
                        <div class="mt-4">
                            <x-input-label for="account_number_to" :value="__('Recipient Account')"
                                           class="text-yellow-950 custom-label mb-2"/>
                            <x-text-input id="account_number_to" class="block mt-4 w-full" type="text" name="account_number_to"
                                          :value="old('account_number_to')"
                                          class="input-field"
                                          placeholder="e.g. {{ $account->account_number }}"
                                          required autofocus autocomplete="account_number_to"/>
                            <x-input-error :messages="$errors->get('account_number')" class="mt-2"/>
                        </div>

                        <div class="mt-4">
                            <x-input-label for="amount" :value="__('Amount')"
                                           class="text-yellow-950 custom-label"/>
                            <input id="amount" class="mt-2 rounded-md" type="number" name="amount"
                                            value="{{ old('amount') }}"
                                            min="0"
                                            step="0.01"
                                            required autofocus autocomplete="amount"/>
                            <span id="currency_symbol" class="ml-2"></span>
                            <span id="currency_symbol_to" class="ml-2"></span>
                            <span id="exchange_rate" class="ml-2"></span>
                            <span id="amount_in_to_currency" class="ml-2"></span>
                        </div>

                        <div class="mt-4">
                            <x-input-label for="description" :value="__('Description')"
                                           class="text-yellow-950 custom-label mb-2"/>
                            <x-text-input id="description" class="block mt-4 w-full" type="text" name="description"
                                          :value="old('description')"
                                          class="input-field"
                                          placeholder="e.g. transaction"
                                          required autofocus autocomplete="description"/>
                            <x-input-error :messages="$errors->get('description')" class="mt-2"/>
                        </div>
                        <div class="flex items-center justify-end mt-4">
                            <x-primary-button class="ms-4 button">
                                {{ __('Transfer') }}
                            </x-primary-button>
                        </div>
                        {{--<script>
                            document.getElementById('account_number_from').addEventListener('change', function () {
                                var selectedOption = this.options[this.selectedIndex];
                                var currencySymbol = selectedOption.getAttribute('data-currency');
                                document.getElementById('currency_symbol').textContent = currencySymbol;
                            });
                            document.getElementById('account_number_to').addEventListener('input', function () {
                                var urlToFetchCurrencyAndRate = '/url/that/returns/currency/and/rate';
                                var accountNumberTo = this.value;
                                fetch(urlToFetchCurrencyAndRate + '?account_number=' + accountNumberTo)
                                    .then(response => response.json())
                                    .then(data => {
                                        document.getElementById('currency_symbol_to').textContent = data.currency;
                                        document.getElementById('exchange_rate').textContent = data.rate;
                                        // Assuming rate is per unit currency of "From Account"
                                        var amountFrom = document.getElementById('amount').value;
                                        var amountTo = amountFrom * data.rate;
                                        document.getElementById('amount_in_to_currency').textContent = amountTo.toFixed(2);
                                    });
                            });
                        </script>--}}
                        <script>
                            document.getElementById('account_number_from').addEventListener('change', function () {
                                updateCurrencySymbol(this, 'currency_symbol');
                            });

                            document.getElementById('account_number_to').addEventListener('input', function () {
                                var urlToFetchCurrencyAndRate = '/url/that/returns/currency/and/rate';
                                var accountNumberTo = this.value;

                                fetch(urlToFetchCurrencyAndRate + '?account_number=' + accountNumberTo)
                                    .then(response => response.json())
                                    .then(data => {
                                        updateCurrencySymbol(this, 'currency_symbol_to');
                                        document.getElementById('exchange_rate').textContent = data.rate;

                                        var amountFrom = document.getElementById('amount').value;
                                        var amountTo = amountFrom * data.rate;
                                        document.getElementById('amount_in_to_currency').textContent = amountTo.toFixed(2);
                                    });
                            });

                            function updateCurrencySymbol(element, targetId) {
                                var selectedOption = element.options[element.selectedIndex];
                                var currencySymbol = selectedOption.getAttribute('data-currency');
                                document.getElementById(targetId).textContent = currencySymbol;
                            }
                        </script>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>



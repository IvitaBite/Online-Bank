<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    use HasFactory;
    //

public function store(Request $request)
{
    /*$validatedData = $request->validate([
        // validation rules here
    ]);


    $exchangeRate = $this->calculateExchangeRate($validatedData['currency_symbol_from'], $validatedData['currency_symbol_to']);


    $validatedData['exchange_rate'] = $exchangeRate;


    Transaction::create($validatedData);

    return redirect()->route('transactions.index');
}
    private function calculateExchangeRate($currencySymbolFrom, $currencySymbolTo)
    {

        $cryptoFrom = CryptoCurrency::where('symbol', $currencySymbolFrom)->first();
        $cryptoTo = CryptoCurrency::where('symbol', $currencySymbolTo)->first();

       if ($cryptoFrom || $cryptoTo) {
            // If one currency is a cryptocurrency
            return $cryptoFrom ? $cryptoFrom->sell_rate : $cryptoTo->buy_rate;
        } else {
            // If neither currency is a cryptocurrency, use the rate from the currency table
            $currencyFrom = Currency::where('symbol', $currencySymbolFrom)->first();
            return $currencyFrom ? $currencyFrom->rate : 1.0; // Default to 1 if rate not found
        }
    }*/
}

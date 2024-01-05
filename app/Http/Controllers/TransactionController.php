<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Currency;
use App\Models\Transaction;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class TransactionController extends Controller
{
    use HasFactory;

    public function create(): View
    {
        $accounts = Auth::user()
            ->accounts()
            ->get()->groupBy('type')
            ->sortBy(function ($accountType) {
                return $accountType->first()['type'];
            });

        return view('transactions.create', ['accounts' => $accounts]);
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'account_number_from' => 'required|exists:accounts,account_number',
            'account_number_to' => 'required|exists:accounts,account_number',
            'amount' => 'required|numeric|min:0.01',
            'description' => 'required|string|max:500',
        ]);

        $senderAccount = Account::where('account_number', $request->account_number_from)
            ->where('user_id', Auth::user()->id)
            ->first();
        $receiverAccount = Account::where('account_number', $request->account_number_to)
            ->where('user_id', Auth::user()->id)
            ->first();

        if ($senderAccount == null || $receiverAccount == null) {
            return redirect()->back()->with('error', 'Invalid account number!');
        }

        $amount = ($request->amount * 1000);

        if ($senderAccount->balance < $amount) {
            return redirect()->back()->with('error', 'Insufficient balance!');
        }

        $senderCurrency = Currency::where('symbol', $senderAccount->currency_symbol)->first()->rate;
        $receiverCurrency = Currency::where('symbol', $receiverAccount->currency_symbol)->first()->rate;

        $exchangeRate = $senderCurrency / $receiverCurrency;
        $convertedAmount = $amount * $exchangeRate;

        $senderAccount->balance -= $amount;
        $receiverAccount->balance += $convertedAmount;
        $senderAccount->save();
        $receiverAccount->save();

        Transaction::create([
            'account_number_from' => $senderAccount->account_number,
            'account_number_to' => $receiverAccount->account_number,
            'amount' => $amount,
            'currency_symbol_from' => $senderAccount->currency_symbol,
            'currency_symbol_to' => $receiverAccount->currency_symbol,
            'exchange_rate' => $exchangeRate,
            'description' => $request->input('description'),
            'type' => 'transfer',
        ]);

        return redirect()->route('transactions.create')->with('success', 'Transaction created successfully');
    }
}

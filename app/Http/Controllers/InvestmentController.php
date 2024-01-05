<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\CryptoCurrency;
use App\Models\Investment;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class InvestmentController extends Controller
{
    use HasFactory;

    public function index(Request $request): View
    {
        $accounts = Auth::user()
            ->accounts()
            ->where('type', 'investment')
            ->get();

        $cryptocurrencies = collect();

        $selectedAccountNr = $request->input('account_number', optional($accounts->first()));
        $selectedAccount = $accounts->firstWhere('account_number', $selectedAccountNr);
        if ($selectedAccount) {
            $currency = $selectedAccount->currency_symbol;
            $cryptocurrencies = CryptoCurrency::where('currency_symbol', $currency)
                ->orderBy('symbol', 'asc')
                ->get();
        }

        return view('investments.index', [
            'accounts' => $accounts,
            'selectedAccount' => $selectedAccount,
            'cryptocurrencies' => $cryptocurrencies,
        ]);
    }


    public function buy(Request $request): RedirectResponse
    {
        $request->validate([
            'account_number' => 'required|exists:accounts,account_number',
            'symbol' => 'required|exists:cryptocurrencies,symbol',
            'amount' => 'required|numeric|min:0.001',
        ]);

        $account = Account::where('account_number', $request->account_number)
            ->where('user_id', Auth::user()->id)
            ->first();

        if (!$account) {
            return redirect()->back()->with('error', 'Account does not exist!');
        }

        $symbol = CryptoCurrency::where('symbol', $request->symbol)->first();
        $buyRate = $symbol->buy_rate;
        $amount = $request->amount;
        $price = $buyRate * $amount;

        if ($account->balance < ($price * 1000)) {
            return redirect()->back()->with('error', 'Insufficient balance!');
        }

        $account->balance -= $price;
        $account->save();

        Investment::create([
            'account_number' => $account->account_number,
            'symbol' => $symbol->symbol,
            'type' => 'crypto',
            'amount' => $amount,
            'buy_rate' => $buyRate,
            'status' => 'active',
        ]);

        return redirect()->back()->with('success', 'Investment created successfully!');
    }

    public function showByAccountName(string $name): View
    {
        $account = Account::where('account_name', $name)
            ->where('user_id', Auth::user()->id)
            ->where('status', 'active')
            ->first();

        if ($account == null) {
            abort(404);
        }

        $investments = $account->investments;

        return view('investments.show', ['account' => $account, 'investments' => $investments]);
    }

    public function sell(Request $request, string $name): RedirectResponse
    {
        $request->validate([
            'account_name' => 'required|exists:accounts,account_name',
            'symbol' => 'required|exists:cryptocurrencies,symbol',
            'amount' => 'required|numeric|min:0.001',
        ]);
        $account = Account::where('account_name', $name)
            ->where('user_id', Auth::user()->id)
            ->first();

        if ($account == null) {
            return redirect()->back()->with('error', 'Invalid account number!');
        }

        $investment = Investment::where('account_number', $account->account_number)->first();

        $symbol = CryptoCurrency::where('symbol', $request->symbol)->first();

        $sellRate = $symbol->sell_rate;
        $amount = $request->amount;

        if ($amount > $investment->amount) {
            return redirect()->back()->with('error', 'Insufficient balance!');
        }

        $price = $sellRate * $amount;

        $newAmount = $investment->amount - $amount;

        if ($newAmount > 0) {
            $status = 'active';
        } else {
            $status = 'sold';
        }

        $account->balance += $price * 1000;
        $account->save();

        $investment->update([
            'sell_rate' => $sellRate,
            'amount' => $newAmount,
            'status' => $status,
        ]);

        return redirect()->route('investments.sell', ['name' => $account->account_name])
            ->with('success', 'Investment sold successfully!');
    }
}

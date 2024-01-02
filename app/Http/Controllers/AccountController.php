<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Account;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;

class AccountController extends Controller
{
    use HasFactory;

    public function showAccounts(string $type): View
    {
        if (!in_array($type, ['checking', 'savings', 'investment'])) {
            abort(404);
        }

        $accounts = auth()->user()->accounts()->where('type', $type)->get();

        return view("accounts.{$type}", ['accounts' => $accounts]);
    }

    public function create(): View
    {
        $validCurrencies = DB::table('currencies')->get();

        return view('accounts.create', ['validCurrencies' => $validCurrencies]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'account_name' => 'required|string|max:255',
            'type' => 'required|in:checking,savings,investment',
            'currency_symbol' => 'required|exists:currencies,symbol',
        ]);

        Auth::user()->accounts()->create([
            'account_name' => $request->input('account_name'),
            'type' => $request->input('type'),
            'currency_symbol' => $request->input('currency_symbol'),
            'status' => 'active',
        ]);

        return redirect()->route('accounts', ['type' => $request->input('type')])
            ->with('message', 'Account created successfully');
    }

    public function showAccountByName(string $name): View
    {
        $account = Account::where('account_name', $name)
            ->where('user_id', Auth::user()->id)
            ->first();

        if($account == null) {
            abort(404);
        }

        return view('accounts.show', ['account' => $account]);
    }

    public function editAccountByName(string $name): View
    {
        $account = Account::where('account_name', $name)
            ->where('user_id', Auth::user()->id)
            ->first();

        if($account == null) {
            abort(404);
        }
        return view('accounts.edit', ['account' => $account]);
    }

    public function update(Request $request, string $name)
    {
        $request->validate([
            'account_name' => 'required|string|max:255',
            'type' => 'required|in:checking,savings,investment',
            'currency_symbol' => 'required|exists:currencies,symbol',
        ]);

        $account = Account::where('account_name', $name)
            ->where('user_id', Auth::user()->id)
            ->first();

        $account->update([
            'account_name' => $request->input('account_name'),
            'type' => $request->input('type'),
            'currency_symbol' => $request->input('currency_symbol'),
        ]);

        return redirect()->route('accounts', ['type' => $request->input('type')])
            ->with('message', 'Account updated successfully');
    }

    public function delete(Account $account)
    {
        $account->delete();

        return redirect()->route('accounts', ['type' => $account->type])
            ->with('message', 'Account deleted successfully');
    }



}

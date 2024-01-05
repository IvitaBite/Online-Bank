<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Account;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;

class AccountController extends Controller
{
    use HasFactory;

    public function showAllAccounts(): View
    {
        $accounts = Account::where('user_id', Auth::user()->id)
            ->orderBy('type')
            ->orderBy('account_name')
            ->get()
            ->groupBy('type');

        return view('dashboard', ['accounts' => $accounts]);
    }

    public function showAccountsByType(string $type): View
    {
        if (!in_array($type, ['checking', 'savings', 'investment'])) {
            abort(404);
        }

        $accounts = Account::where('user_id', Auth::user()->id)
            ->where('type', $type)
            ->get();

        return view("accounts.{$type}", ['accounts' => $accounts]);
    }

    public function showAccountByName(string $name): View
    {
        $account = Account::where('account_name', $name)
            ->where('user_id', Auth::user()->id)
            ->first();

        if ($account == null) {
            abort(404);
        }

        return view('accounts.show', ['account' => $account]);
    }

    public function create(): View
    {
        $validCurrencies = DB::table('currencies')->get();

        return view('accounts.create', ['validCurrencies' => $validCurrencies]);
    }

    public function store(Request $request): RedirectResponse
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
            ->with('success', 'Account created successfully!');
    }

    public function editAccountByName(string $name): View
    {
        $account = Account::where('account_name', $name)
            ->where('user_id', Auth::user()->id)
            ->first();

        if ($account == null) {
            abort(403);
        }

        return view('accounts.edit', ['account' => $account]);
    }

    public function update(Request $request, string $name): RedirectResponse
    {
        $request->validate([
            'account_name' => 'required|string|max:255',
        ]);

        $account = Account::where('account_name', $name)
            ->where('user_id', Auth::user()->id)
            ->first();

        $account->update([
            'account_name' => $request->input('account_name'),
        ]);

        return redirect()->route('accounts.edit', ['name' => $account->account_name])
            ->with('success', 'Account updated successfully');
    }

    public function block(Request $request, string $name): RedirectResponse
    {
        $request->validateWithBag('accountBlock', [
            'password' => ['required', 'current_password'],
        ]);

        $account = Account::where('account_name', $name)
            ->where('user_id', Auth::user()->id)
            ->first();


        if ($account == null) {
            abort(403);
        }

        if ($account->status == 'active') {
            $account->status = 'blocked';
            $message = 'Account blocked successfully!';
        } else {
            $account->status = 'active';
            $message = 'Account activated successfully!';
        }

        $account->save();

        return redirect()->route('accounts.edit', ['name' => $account->account_name])
            ->with('success', $message);
    }

    public function destroy(Request $request, string $name): RedirectResponse //todo pielabot ar saving account lietu!! pareizi izdzeest investment account
    {
        $request->validateWithBag('accountDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $account = Account::where('account_name', $name)
            ->where('user_id', Auth::user()->id)
            ->first();

        if ($account == null) {
            abort(403);
        }

        if ($account->type == 'investment') {

            if ($account->investments()
                ->where('status', 'active')
                ->exists()
            ) {

                return redirect()->route('accounts.edit', ['name' => $account->account_name])
                    ->with('error', 'Cannot delete investment account if it has active investments!');
            }
        }

        if ($account->balance != 0) {
            return redirect()->route('accounts.edit', ['name' => $account->account_name])
                ->with('error', 'Cannot delete account with non-zero balance!');
        }

        $account->delete();

        return redirect()->route('accounts', ['type' => $account->type])
            ->with('success', 'Account deleted successfully!');
    }
}

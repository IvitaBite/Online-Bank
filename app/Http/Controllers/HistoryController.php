<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Investment;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class HistoryController extends Controller
{
    public function showAllTransaction(): View
    {
        $transactions = Transaction::with(['fromAccount', 'toAccount'])
            ->whereHas('fromAccount', function ($query) {
                $query->where('user_id', Auth::user()->id);
            })
            ->orWhereHas('toAccount', function ($query) {
                $query->where('user_id', Auth::user()->id);
            })
            ->orderByDesc('created_at')
            ->get();

        $investments = Investment::whereHas('account', function ($query) {
            $query->where('user_id', Auth::id());
        })->orderBy('created_at', 'desc')->get();

        return view('history', [
            'transactions' => $transactions,
            'investments' => $investments,
        ]);
    }
}

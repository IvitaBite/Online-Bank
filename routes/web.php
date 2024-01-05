<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\HistoryController;
use App\Http\Controllers\InvestmentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TransactionController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware('auth')->group(function () {
    Route::get('accounts/create', [AccountController::class, 'create'])->name('accounts.create');
    Route::post('accounts', [AccountController::class, 'store'])->name('accounts.store');
    Route::get('accounts/{type}', [AccountController::class, 'showAccountsByType'])->name('accounts');
    Route::get('account/{name}', [AccountController::class, 'showAccountByName'])->name('accounts.show');
    Route::get('account/{name}/edit', [AccountController::class, 'editAccountByName'])->name('accounts.edit');
    Route::put('account/{name}/edit', [AccountController::class, 'update'])->name('accounts.update');
    Route::patch('account/{name}/edit', [AccountController::class, 'block'])->name('accounts.block');
    Route::delete('accounts/{type}', [AccountController::class, 'destroy'])->name('accounts.destroy');
    Route::get('/dashboard', [AccountController::class, 'showAllAccounts'])->name('dashboard');
});

Route::middleware('auth')->group(function () {
    Route::get('transaction', [TransactionController::class, 'create'])->name('transactions.create');
    Route::post('transaction', [TransactionController::class, 'store'])->name('transactions.store');
});

Route::middleware('auth')->group(function () {
    Route::get('/investment', [InvestmentController::class, 'index'])->name('investments.index');
    Route::post('/investment', [InvestmentController::class, 'buy'])->name('investments.buy');
    Route::get('investment/{name}', [InvestmentController::class, 'showByAccountName'])->name('investments.show');
    Route::post('account/{name}', [InvestmentController::class, 'sell'])->name('investments.sell');
});

Route::middleware('auth')->group(function () {
    Route::get('/history', [HistoryController::class, 'showAllTransaction'])->name('history');
});



require __DIR__.'/auth.php';

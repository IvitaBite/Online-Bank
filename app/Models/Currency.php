<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Currency extends Model
{
    protected $table = 'currencies';
    protected $primaryKey = 'symbol';
    public $incrementing = false;
    public $timestamps = true;

    protected $fillable = [
        'symbol',
        'name',
        'rate',
    ];

    public static array $validCurrencies = [
        'USD',
        'EUR',
        'GBP',
        'NOK',
        'DKK',
        'CZK',
        'CHF',
        'SEK',
        'PLN',
        'JPY',
        'BGN',
        'HUF',
        'RON',
        'ISK',
        'HRK',
        'TRY',
        'AUD',
        'BRL',
        'CAD',
        'CNY',
        'HKD',
        'IDR',
        'ILS',
        'INR',
        'KRW',
        'MXN',
        'MYR',
        'NZD',
        'PHP',
        'SGD',
        'THB',
        'ZAR',
    ];

    public function cryptocurrencies(): HasMany
    {
        return $this->hasMany(CryptoCurrency::class, 'currency_symbol', 'symbol');
    }

    public function accounts(): HasMany
    {
        return $this->hasMany(Account::class, 'currency_symbol', 'symbol');
    }
}

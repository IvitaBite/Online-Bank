<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CryptoCurrency extends Model
{
    protected $table = 'cryptocurrencies';
    protected $primaryKey = 'pair';
    public $incrementing = false;
    public $timestamps = true;

    protected $fillable = [
        'pair',
        'symbol',
        'name',
        'currency_symbol',
        'buy_rate',
        'sell_rate',
    ];

    public static array $validCryptoCurrencies = [
        'BTC',
        'ETH',
        'USDT',
        'SOL',
        'XRP',
        'USDC',
        'ADA',
        'AVAX',
        'DOGE',
        'DOT',
        'LINK',
        'MATIC',
        'SHIB',
        'DAI',
        'LTC',
        'BCH',
        'ICP',
        'ATOM',
        'UNI',
        'XLM',
        'NEAR',
        'INJ',
        'ETC',
        'HBAR',
        'IMX',
        'OP',
    ];

    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class, 'currency_symbol', 'symbol');
    }
}

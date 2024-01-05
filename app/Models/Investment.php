<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Investment extends Model
{
    use HasFactory;

    protected $table = 'investments';

    public $timestamps = true;

    protected $fillable = [
        'account_number',
        'symbol',
        'type',
        'amount',
        'buy_rate',
        'sell_rate',
        'status',
    ];

    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'account_number', 'account_number');
    }

    public function cryptocurrency()
    {
        return $this->belongsTo(CryptoCurrency::class, 'symbol', 'symbol');
    }
}

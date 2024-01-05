<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaction extends Model
{
    use HasFactory;

    protected $table = 'transactions';

    public $timestamps = true;

    protected $fillable = [
        'account_number_from',
        'account_number_to',
        'amount',
        'currency_symbol_from',
        'currency_symbol_to',
        'exchange_rate',
        'description',
        'type',
    ];

    public function fromAccount(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'account_number_from', 'account_number');
    }

    public function toAccount(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'account_number_to', 'account_number');
    }
}

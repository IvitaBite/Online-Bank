<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Account extends Model
{
    use HasFactory;

    protected $table = 'accounts';
    protected $primaryKey = 'account_number';
    protected const MIN_BAL = 1000;
    protected const MAX_BAL = 100000;
    protected const COUNTRY_CODE = 'LV';
    protected const BANK_IDENTIFIER = 'BUZZ';
    protected const CHECK_DIGITS = '11';
    public $incrementing = false;
    public $timestamps = true;

    protected $fillable = [
        'account_name',
        'type',
        'currency_symbol',
        'status',
    ];

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function (Account $account) {
            $account->account_number = $account->generateIban();

            if (strcasecmp($account->type, 'Checking') === 0) {
                $account->balance = $account->generateInitialBalance();
            }
        });
    }

    private function generateIban(): string
    {
        return self::COUNTRY_CODE . self::CHECK_DIGITS . self::BANK_IDENTIFIER . $this->generateLocalAccountNumber();
    }

    private function generateLocalAccountNumber(): string
    {
        return strval(rand(1000000000, 9999999999));
    }

    protected function generateInitialBalance(): int
    {
        $balance = rand(self::MIN_BAL, self::MAX_BAL);

        return $balance * 1000;
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class, 'currency_symbol', 'symbol');
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class, 'account_number_from')
            ->orWhere('account_number_to','account_number');
    }
}

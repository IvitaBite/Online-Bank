<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\Currency;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Console\Command;

class FetchCurrencyRatesCommand extends Command
{
    protected $signature = 'currencies:fetch';

    protected $description = 'Fetch all currency rates from API and update the rates in the database';

    private const BASE_URL = 'https://api.coinbase.com/v2/';
    private const  BASE_CURRENCY = 'USD';

    public function handle(): int
    {
        $client = new Client();

        try {
            $responseCurrencies = $client->get(self::BASE_URL . 'currencies');
            $responseCurrencies = json_decode($responseCurrencies->getBody()->getContents());

            $responseRate = $client->get(self::BASE_URL . 'exchange-rates?currency=' . self::BASE_CURRENCY);
            $responseRate = json_decode($responseRate->getBody()->getContents());

            foreach ($responseCurrencies->data as $currency) {
                if (!in_array($currency->id, Currency::$validCurrencies)) {
                    continue;
                }

                $rate = $responseRate->data->rates->{$currency->id} ?? null;

                Currency::updateOrCreate([
                    'symbol' => $currency->id,
                    'name' => $currency->name,
                ], [
                    'rate' => $rate,
                ]);
            }
        } catch (GuzzleException $e) {
            return 1;
        }
        return 0;
    }
}

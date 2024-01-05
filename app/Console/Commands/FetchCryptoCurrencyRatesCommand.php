<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\CryptoCurrency;
use App\Models\Currency;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Console\Command;

class FetchCryptoCurrencyRatesCommand extends Command
{
    protected $signature = 'crypto:fetch';

    protected $description = 'Fetch all cryptocurrency rates from API and update the rates in the database.';

    private const BASE_URL = 'https://api.coinbase.com/v2/'; //todo env file jo atkārtojas


    public function handle(): int //todo pielabot try and catch
    {
        $client = new Client();

        try {
            $cryptoData = $this->fetchData($client);

            foreach (Currency::$validCurrencies as $currency) {
                $this->updateRates($cryptoData, $currency, $client);
            }

        } catch (GuzzleException $e) {
            return 1;
        }
        return 0;
    }

    private function fetchData(Client $client): array
    {
        $response = $client->get(self::BASE_URL . 'currencies/crypto');
        $cryptoData = json_decode($response->getBody()->getContents());

        return $cryptoData->data ?? [];
    }

    private function updateRates(
        array  $cryptoData,
        string $currency,
        Client $client
    ): void
    {
        foreach ($cryptoData as $crypto) {
            $symbol = $crypto->code;

            if (!in_array($symbol, CryptoCurrency::$validCryptoCurrencies)) {
                continue;
            }

            $buyRate = $this->fetchRate(
                $symbol,
                'buy',
                $currency,
                $client
            );
            $sellRate = $this->fetchRate(
                $symbol,
                'sell',
                $currency,
                $client
            );

            $this->update(
                $symbol,
                $buyRate,
                $sellRate,
                $currency,
                $crypto->name
            );
        }
    }

    private function fetchRate(
        string $symbol,
        string $type,
        string $currency,
        Client $client
    ): float
    {
        $pair = $symbol . '-' . $currency;
        $response = $client->get(self::BASE_URL . "prices/{$pair}/{$type}");
        $rateData = json_decode($response->getBody()->getContents());

        return (float)$rateData->data->amount;
    }

    private function update(
        string $symbol,
        float  $buyRate,
        float  $sellRate,
        string $currency,
        string $cryptoName
    ): void
    {
        $currencyModel = Currency::where('symbol', $currency)->firstOrFail();
        $currencyModel->cryptocurrencies()->updateOrCreate([
            'pair' => $symbol . '-' . $currency,
            'symbol' => $symbol,
            'name' => $cryptoName,
        ], [
            'buy_rate' => $buyRate,
            'sell_rate' => $sellRate,
        ]);
    }
}

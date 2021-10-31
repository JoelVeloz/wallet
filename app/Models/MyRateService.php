<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Bavix\Wallet\Interfaces\Wallet;
use Bavix\Wallet\Services\WalletService;
use Illuminate\Support\Arr;

class MyRateService extends \Bavix\Wallet\Simple\Rate
{

    // list of exchange rates (take from the database)
    protected $rates = [
        'USD' => [
            'RUB' => 67.61,
            'COP' => 3809.09,
        ],
        'RUB' => [
            'USD' => 0.0147907114,
        ],
        'COP' => [
            'USD' => 0.00026,
        ],
    ];

    protected function rate(Wallet $wallet)
    {
        $from = app(WalletService::class)->getWallet($this->withCurrency);
        $to = app(WalletService::class)->getWallet($wallet);

        return Arr::get(
            Arr::get($this->rates, $from->currency, []),
            $to->currency,
            1
        );
    }

    public function convertTo(Wallet $wallet)
    {
        return parent::convertTo($wallet) * $this->rate($wallet);
    }

}

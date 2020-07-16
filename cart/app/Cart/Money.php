<?php

namespace App\Cart;

use Money\Currency;
use NumberFormatter;
use Money\Money as BaseMoney;
use Money\Currencies\ISOCurrencies;
use Money\Formatter\IntlMoneyFormatter;

class Money
{
    protected $money;

    public function __construct($value)
    {
        $this->money = new BaseMoney($value, new Currency('PEN'));
    }

    public function amount()
    {
        return $this->money->getAmount();
    }

    public function formatted()
    {
        $formatter = new IntlMoneyFormatter(
            new NumberFormatter('es_PE', NumberFormatter::CURRENCY),
            new ISOCurrencies()
        );

        return $formatter->format($this->money);
    }
}

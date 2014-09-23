<?php

namespace Psamatt\Pecunia\Domain\SharedKernel;

use Money\Currency;

class CurrencyFactory
{
	protected static $currency;
    
    public static function setCurrencyName($currency)
    {
        self::$currency = $currency;
    }
 
    public static function getCurrency()
    {
        return new Currency(self::$currency);
    }
}
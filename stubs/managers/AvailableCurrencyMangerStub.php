<?php

namespace Pantagruel74\MulticurtestAccountAdministrationsServiceStubs\managers;

use Pantagruel74\MulticurtestAccountAdministrationsService\managers\AvailableCurrencyMangerInterface;

class AvailableCurrencyMangerStub implements AvailableCurrencyMangerInterface
{
    const RUB = "RUB";
    const USD = "USD";
    const EUR = "EUR";

    private $curList = [self::RUB, self::USD, self::EUR];

    public function isCurrenciesAvailable(array $curIds): bool
    {
        foreach ($curIds as $c) {
            if(!in_array($c, $this->curList)) {
                return false;
            }
        }
        return true;
    }
}
<?php

namespace Pantagruel74\MulticurtestAccountAdministrationsService\managers;

interface AvailableCurrencyMangerInterface
{
    /* @param string[] $curIds */
    public function isCurrenciesAvailable(array $curIds): bool;
}
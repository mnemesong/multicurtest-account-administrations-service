<?php

namespace Admin\MulticurtestAccountAdministrationsService\managers;

interface AvailableCurrencyMangerInterface
{
    /* @param string[] $curIds */
    public function isCurrenciesAvailable(array $curIds): bool;
}
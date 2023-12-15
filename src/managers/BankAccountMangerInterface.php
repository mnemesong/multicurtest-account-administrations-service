<?php

namespace Pantagruel74\MulticurtestAccountAdministrationsService\managers;

use Pantagruel74\MulticurtestAccountAdministrationsService\records\BankAccountRecInterface;

interface BankAccountMangerInterface
{
    public function createAccount(
        string $customerId,
        string $mainCurrency
    ): BankAccountRecInterface;

    public function getAccount(string $accId): BankAccountRecInterface;

    /* @param BankAccountRecInterface[] */
    public function saveBankAccounts(
        array $accounts
    ): void;
}
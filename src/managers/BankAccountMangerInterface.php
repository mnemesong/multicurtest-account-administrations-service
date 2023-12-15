<?php

namespace Admin\MulticurtestAccountAdministrationsService\managers;

use Admin\MulticurtestAccountAdministrationsService\records\BankAccountRecInterface;

interface BankAccountMangerInterface
{
    public function createAccount(
        string $customerId,
        string $mainCurrency
    ): BankAccountRecInterface;

    public function getAccount(string $accId): BankAccountRecInterface;
}
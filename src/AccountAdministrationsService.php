<?php

namespace Admin\MulticurtestAccountAdministrationsService;

use Admin\MulticurtestAccountAdministrationsService\managers\AvailableCurrencyMangerInterface;
use Admin\MulticurtestAccountAdministrationsService\managers\BankAccountMangerInterface;
use Admin\MulticurtestAccountAdministrationsService\managers\CustomerManagerInterface;
use Webmozart\Assert\Assert;

final class AccountAdministrationsService
{
    private CustomerManagerInterface $customerManager;
    private AvailableCurrencyMangerInterface $availableCurrencyManger;
    private BankAccountMangerInterface $bankAccountManger;

    /**
     * @param CustomerManagerInterface $customerManager
     * @param AvailableCurrencyMangerInterface $availableCurrencyManger
     * @param BankAccountMangerInterface $bankAccountManger
     */
    public function __construct(
        CustomerManagerInterface $customerManager,
        AvailableCurrencyMangerInterface $availableCurrencyManger,
        BankAccountMangerInterface $bankAccountManger
    ) {
        $this->customerManager = $customerManager;
        $this->availableCurrencyManger = $availableCurrencyManger;
        $this->bankAccountManger = $bankAccountManger;
    }

    public function createAccountWithOneCurrency(
        string $customerId,
        string $mainCurrency
    ): void {
        Assert::true(
            $this->customerManager->isCustomerExists($customerId),
            "Customer " . $customerId . " is not exists"
        );
        Assert::true(
            $this
            ->availableCurrencyManger
            ->isCurrenciesAvailable([$mainCurrency]),
            "Currency " . $mainCurrency . " is not available"
        );
        $newBankAccount = $this->bankAccountManger
            ->createAccount($customerId, $mainCurrency);
        $newBankAccount->save();
    }

    /* @param string[] $currencies */
    public function addCurrenciesToAccount(
        string $accountId,
        array $currencies
    ): void {
        $acc = $this->bankAccountManger->getAccount($accountId);
        $accCurrencies = $acc->getCurrencies();
        $currsToAdd = array_filter(
            $currencies,
            fn($c) => !in_array($c, $accCurrencies)
        );
        Assert::true(
            $this->availableCurrencyManger->isCurrenciesAvailable($currsToAdd),
            "Some of currencies to add: "
                . implode(", ", $currsToAdd)
                . " are not available"
        );
        $acc = $acc->addCurrencies($currsToAdd);
        $acc->save();
    }

    public function setMainCurrencyToAccount(
        string $accountId,
        string $curId
    ): void {
        $acc = $this->bankAccountManger->getAccount($accountId);
        $accCurrencies = $acc->getCurrencies();
        Assert::inArray(
            $curId,
            $accCurrencies,
            "Account " . $accountId
                . " is not contains currency" .$curId
        );
        $acc = $acc->withMainCurrency($curId);
        $acc->save();
    }
}
<?php

namespace Pantagruel74\MulticurtestAccountAdministrationsService;

use Pantagruel74\MulticurtestAccountAdministrationsService\managers\AvailableCurrencyMangerInterface;
use Pantagruel74\MulticurtestAccountAdministrationsService\managers\BankAccountMangerInterface;
use Pantagruel74\MulticurtestAccountAdministrationsService\managers\CustomerManagerInterface;
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
        $this->bankAccountManger->saveBankAccounts([$newBankAccount]);
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
        $this->bankAccountManger->saveBankAccounts([$acc]);
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
        $this->bankAccountManger->saveBankAccounts([$acc]);
    }

    /* @return string[] */
    public function getListOfCurrenciesInAccount(
        string $accountId
    ): array {
        $acc = $this->bankAccountManger->getAccount($accountId);
        return $acc->getCurrencies();
    }

    public function getMainCurrencyOfAccount(
        string $accountId
    ): string {
        $acc = $this->bankAccountManger->getAccount($accountId);
        return $acc->getMainCurrency();
    }
}
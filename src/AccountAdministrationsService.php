<?php

namespace Pantagruel74\MulticurtestAccountAdministrationsService;

use Pantagruel74\MulticurtestAccountAdministrationsService\managers\AvailableCurrencyMangerInterface;
use Pantagruel74\MulticurtestAccountAdministrationsService\managers\BankAccountMangerInterface;
use Webmozart\Assert\Assert;

final class AccountAdministrationsService
{
    private AvailableCurrencyMangerInterface $availableCurrencyManger;
    private BankAccountMangerInterface $bankAccountManger;

    /**
     * @param AvailableCurrencyMangerInterface $availableCurrencyManger
     * @param BankAccountMangerInterface $bankAccountManger
     */
    public function __construct(
        AvailableCurrencyMangerInterface $availableCurrencyManger,
        BankAccountMangerInterface $bankAccountManger
    ) {
        $this->availableCurrencyManger = $availableCurrencyManger;
        $this->bankAccountManger = $bankAccountManger;
    }

    /**
     * @param string $mainCurrency
     * @return void
     */
    public function createAccountWithOneCurrency(
        string $mainCurrency
    ): void {
        Assert::true(
            $this
            ->availableCurrencyManger
            ->isCurrenciesAvailable([$mainCurrency]),
            "Currency " . $mainCurrency . " is not available"
        );
        $newBankAccount = $this->bankAccountManger
            ->createAccount($mainCurrency);
        $this->bankAccountManger->saveBankAccounts([$newBankAccount]);
    }

    /**
     * @param string $accountId
     * @param string[] $currencies
     * @return void
     */
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

    /**
     * @param string $accountId
     * @param string $curId
     * @return void
     */
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

    /**
     * @param string $accountId
     * @return string[]
     */
    public function getListOfCurrenciesInAccount(
        string $accountId
    ): array {
        $acc = $this->bankAccountManger->getAccount($accountId);
        return $acc->getCurrencies();
    }

    /**
     * @param string $accountId
     * @return string
     */
    public function getMainCurrencyOfAccount(
        string $accountId
    ): string {
        $acc = $this->bankAccountManger->getAccount($accountId);
        return $acc->getMainCurrency();
    }
}
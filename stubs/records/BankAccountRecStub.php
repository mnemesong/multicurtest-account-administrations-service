<?php

namespace Pantagruel74\MulticurtestAccountAdministrationsServiceStubs\records;

use Pantagruel74\MulticurtestAccountAdministrationsService\records\BankAccountRecInterface;
use Webmozart\Assert\Assert;

class BankAccountRecStub implements BankAccountRecInterface
{
    private string $id;
    private array $currencies;
    private string $mainCurrency;

    /**
     * @param string $id
     * @param string[] $currencies
     * @param string $mainCurrency
     */
    public function __construct(
        string $id,
        array $currencies,
        string $mainCurrency
    ) {
        $this->id = $id;
        $this->currencies = $currencies;
        $this->mainCurrency = $mainCurrency;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getCurrencies(): array
    {
        return $this->currencies;
    }

    public function getMainCurrency(): string
    {
        return $this->mainCurrency;
    }

    public function addCurrencies(array $curs): BankAccountRecInterface
    {
        $c = clone $this;
        $c->currencies = array_merge(
            $curs,
            $this->currencies
        );
        return $c;
    }

    public function withMainCurrency(string $cur): BankAccountRecInterface
    {
        Assert::inArray($cur, $this->currencies);
        $c = clone $this;
        $c->mainCurrency = $cur;
        return $c;
    }
}
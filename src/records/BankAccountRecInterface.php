<?php

namespace Pantagruel74\MulticurtestAccountAdministrationsService\records;

interface BankAccountRecInterface
{
    public function getId(): string;
    /* @return string[] */
    public function getCurrencies(): array;
    public function getMainCurrency(): string;
    public function addCurrencies(array $curs): self;
    public function withMainCurrency(string $cur): self;
}
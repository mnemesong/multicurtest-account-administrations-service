<?php

namespace Pantagruel74\MulticurtestAccountAdministrationsServiceStubs\managers;

use Pantagruel74\MulticurtestAccountAdministrationsService\managers\BankAccountMangerInterface;
use Pantagruel74\MulticurtestAccountAdministrationsService\records\BankAccountRecInterface;
use Pantagruel74\MulticurtestAccountAdministrationsServiceStubs\records\BankAccountRecStub;
use Ramsey\Uuid\Uuid;
use Webmozart\Assert\Assert;

class BankAccountManagerStub implements BankAccountMangerInterface
{
    const EXISTS_ACC_ID = "ca9ad4c6-7542-4339-98bd-451f0b51280b";

    private array $accs = [];

    /**
     * @param BankAccountRecStub[] $accs
     */
    public function __construct(array $accs)
    {
        Assert::allIsAOf($accs, BankAccountRecStub::class);
        $this->accs = $accs;
    }

    public function createAccount(
        string $mainCurrency
    ): BankAccountRecInterface {
        return new BankAccountRecStub(
            Uuid::uuid4()->toString(),
            [$mainCurrency],
            $mainCurrency
        );
    }

    public function getAccount(string $accId): BankAccountRecInterface
    {
        $accs = array_filter(
            $this->accs,
            fn(BankAccountRecInterface $acc) => ($acc->getId() === $accId)
        );
        Assert::count($accs, 1);
        return current($accs);
    }

    public function saveBankAccounts(array $accounts): void
    {
        Assert::allIsAOf($accounts, BankAccountRecStub::class);
        /* @var BankAccountRecStub[] $accounts */
        $storedAccUuids = array_map(
            fn(BankAccountRecStub $acc) => $acc->getId(),
            $this->accs
        );
        foreach ($accounts as $acc) {
            $accIndex = array_search($acc->getId(), $storedAccUuids);
            if($accIndex !== false) {
                $this->accs[$accIndex] = $acc;
            } else {
                $this->accs[] = $acc;
            }
        }
    }

    /* @return BankAccountRecStub[] */
    public function getAllAccounts(): array
    {
        return $this->accs;
    }
}
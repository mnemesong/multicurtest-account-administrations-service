<?php

namespace Pantagruel74\MulticurtestAccountAdministrationsServiceTest;

use Pantagruel74\MulticurtestAccountAdministrationsService\AccountAdministrationsService;
use Pantagruel74\MulticurtestAccountAdministrationsServiceStubs\managers\AvailableCurrencyMangerStub;
use Pantagruel74\MulticurtestAccountAdministrationsServiceStubs\managers\BankAccountManagerStub;
use Pantagruel74\MulticurtestAccountAdministrationsServiceStubs\managers\CustomerManagerStub;
use Pantagruel74\MulticurtestAccountAdministrationsServiceStubs\records\BankAccountRecStub;
use PHPUnit\Framework\TestCase;

class AccountAdministrationsServiceTest extends TestCase
{
    public function testCreateAccountValid()
    {
        $curManager = new AvailableCurrencyMangerStub();
        $custManager = new CustomerManagerStub();
        $accManager = new BankAccountManagerStub([]);
        $service = new AccountAdministrationsService(
            $custManager,
            $curManager,
            $accManager
        );
        $this->assertEmpty($accManager->getAllAccounts());
        $service->createAccountWithOneCurrency(
            CustomerManagerStub::CUSTOMER_ID,
            AvailableCurrencyMangerStub::EUR
        );
        $accs = $accManager->getAllAccounts();
        $this->assertCount(1, $accs);
        $this->assertEquals(
            AvailableCurrencyMangerStub::EUR,
            $accs[0]->getMainCurrency()
        );
    }
}
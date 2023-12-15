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

    public function testCreateAccountInvalidCustomer()
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
        $this->expectException(\InvalidArgumentException::class);
        $service->createAccountWithOneCurrency(
            "ahds7yfvbasib89",
            AvailableCurrencyMangerStub::EUR
        );
    }

    public function testCreateAccountInvalidCurrency()
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
        $this->expectException(\InvalidArgumentException::class);
        $service->createAccountWithOneCurrency(
            CustomerManagerStub::CUSTOMER_ID,
            "BUBL"
        );
    }

    public function testAddCurrenciesToAccountValid()
    {
        $accRecId = "38712984y918";
        $curManager = new AvailableCurrencyMangerStub();
        $custManager = new CustomerManagerStub();
        $accManager = new BankAccountManagerStub([
            new BankAccountRecStub($accRecId, ["EUR"], "EUR")
        ]);
        $service = new AccountAdministrationsService(
            $custManager,
            $curManager,
            $accManager
        );
        $service->addCurrenciesToAccount($accRecId, ["RUB", "EUR", "USD"]);
        $acc = $accManager->getAllAccounts()[0];
        $this->assertTrue(in_array("RUB", $acc->getCurrencies()));
        $this->assertTrue(in_array("EUR", $acc->getCurrencies()));
        $this->assertTrue(in_array("USD", $acc->getCurrencies()));
        $this->assertEquals($acc->getId(), $accRecId);
        $this->assertEquals($acc->getMainCurrency(), "EUR");
    }

    public function testAddCurrenciesToAccountInvalidAccId()
    {
        $accRecId = "38712984y918";
        $curManager = new AvailableCurrencyMangerStub();
        $custManager = new CustomerManagerStub();
        $accManager = new BankAccountManagerStub([
            new BankAccountRecStub($accRecId, ["EUR"], "EUR")
        ]);
        $service = new AccountAdministrationsService(
            $custManager,
            $curManager,
            $accManager
        );
        $this->expectException(\InvalidArgumentException::class);
        $service->addCurrenciesToAccount("31", ["RUB", "EUR", "USD"]);
    }

    public function testAddCurrenciesToAccountInvalidCurrency()
    {
        $accRecId = "38712984y918";
        $curManager = new AvailableCurrencyMangerStub();
        $custManager = new CustomerManagerStub();
        $accManager = new BankAccountManagerStub([
            new BankAccountRecStub($accRecId, ["EUR"], "EUR")
        ]);
        $service = new AccountAdministrationsService(
            $custManager,
            $curManager,
            $accManager
        );
        $this->expectException(\InvalidArgumentException::class);
        $service->addCurrenciesToAccount($accRecId, ["RUB", "EUR", "BOBAS"]);
    }

    public function testSetMainCurrencyToAccountValid()
    {
        $accRecId = "38712984y918";
        $curManager = new AvailableCurrencyMangerStub();
        $custManager = new CustomerManagerStub();
        $accManager = new BankAccountManagerStub([
            new BankAccountRecStub($accRecId, ["EUR", "RUB"], "EUR")
        ]);
        $service = new AccountAdministrationsService(
            $custManager,
            $curManager,
            $accManager
        );
        $service->setMainCurrencyToAccount($accRecId, "RUB");
        $acc = $accManager->getAllAccounts()[0];
        $this->assertTrue(in_array("RUB", $acc->getCurrencies()));
        $this->assertTrue(in_array("EUR", $acc->getCurrencies()));
        $this->assertEquals($acc->getId(), $accRecId);
        $this->assertEquals($acc->getMainCurrency(), "RUB");
    }

    public function testSetMainCurrencyToAccountInvalidAccId()
    {
        $accRecId = "38712984y918";
        $curManager = new AvailableCurrencyMangerStub();
        $custManager = new CustomerManagerStub();
        $accManager = new BankAccountManagerStub([
            new BankAccountRecStub($accRecId, ["EUR", "RUB"], "EUR")
        ]);
        $service = new AccountAdministrationsService(
            $custManager,
            $curManager,
            $accManager
        );
        $this->expectException(\InvalidArgumentException::class);
        $service->setMainCurrencyToAccount("138", "RUB");
    }

    public function testSetMainCurrencyToAccountInvalidCurreencyToChange()
    {
        $accRecId = "38712984y918";
        $curManager = new AvailableCurrencyMangerStub();
        $custManager = new CustomerManagerStub();
        $accManager = new BankAccountManagerStub([
            new BankAccountRecStub($accRecId, ["EUR", "RUB"], "EUR")
        ]);
        $service = new AccountAdministrationsService(
            $custManager,
            $curManager,
            $accManager
        );
        $this->expectException(\InvalidArgumentException::class);
        $service->setMainCurrencyToAccount($accRecId, "USD");
    }

    public function testGetListOfCurrenciesInAccountValid()
    {
        $accRecId = "38712984y918";
        $curManager = new AvailableCurrencyMangerStub();
        $custManager = new CustomerManagerStub();
        $accManager = new BankAccountManagerStub([
            new BankAccountRecStub($accRecId, ["EUR", "RUB"], "EUR")
        ]);
        $service = new AccountAdministrationsService(
            $custManager,
            $curManager,
            $accManager
        );
        $cursList = $service->getListOfCurrenciesInAccount($accRecId);
        $this->assertTrue(in_array("RUB", $cursList));
        $this->assertTrue(in_array("EUR", $cursList));
    }

    public function testGetListOfCurrenciesInAccountInvalidAccId()
    {
        $accRecId = "38712984y918";
        $curManager = new AvailableCurrencyMangerStub();
        $custManager = new CustomerManagerStub();
        $accManager = new BankAccountManagerStub([
            new BankAccountRecStub($accRecId, ["EUR", "RUB"], "EUR")
        ]);
        $service = new AccountAdministrationsService(
            $custManager,
            $curManager,
            $accManager
        );
        $this->expectException(\InvalidArgumentException::class);
        $cursList = $service->getListOfCurrenciesInAccount("173812");
    }

    public function testGetMainCurrencyInAccountValid()
    {
        $accRecId = "38712984y918";
        $curManager = new AvailableCurrencyMangerStub();
        $custManager = new CustomerManagerStub();
        $accManager = new BankAccountManagerStub([
            new BankAccountRecStub($accRecId, ["EUR", "RUB"], "EUR")
        ]);
        $service = new AccountAdministrationsService(
            $custManager,
            $curManager,
            $accManager
        );
        $mainCur = $service->getMainCurrencyOfAccount($accRecId);
        $this->assertEquals("EUR", $mainCur);
    }

    public function testGetMainCurrencyInAccountInvalidAccId()
    {
        $accRecId = "38712984y918";
        $curManager = new AvailableCurrencyMangerStub();
        $custManager = new CustomerManagerStub();
        $accManager = new BankAccountManagerStub([
            new BankAccountRecStub($accRecId, ["EUR", "RUB"], "EUR")
        ]);
        $service = new AccountAdministrationsService(
            $custManager,
            $curManager,
            $accManager
        );
        $this->expectException(\InvalidArgumentException::class);
        $mainCur = $service->getMainCurrencyOfAccount("1h29");
    }
}
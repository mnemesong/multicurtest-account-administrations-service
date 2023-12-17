# multicurtest-account-administrations-service
Divan.ru test task: Service for adminitration bank-accounts by customer


## Description
Service for account administration operations.


## Source structure
- managers
  - AvailableCurrencyManagerInterface
  - BankAccountManagerInterface
- records
  - BankAccountRecInterface
AccountAdministrationService


## API
```php
<?php
namespace Pantagruel74\MulticurtestAccountAdministrationsService;

final class AccountAdministrationsService
{
    /**
     * @param string $mainCurrency
     * @return void
     */
    public function createAccountWithOneCurrency(
        string $mainCurrency
    ): void {...}

    /**
     * @param string $accountId
     * @param string[] $currencies
     * @return void
     */
    public function addCurrenciesToAccount(
        string $accountId,
        array $currencies
    ): void {...}

    /**
     * @param string $accountId
     * @param string $curId
     * @return void
     */
    public function setMainCurrencyToAccount(
        string $accountId,
        string $curId
    ): void {...}

    /**
     * @param string $accountId
     * @return string[]
     */
    public function getListOfCurrenciesInAccount(
        string $accountId
    ): array {...}

    /**
     * @param string $accountId
     * @return string
     */
    public function getMainCurrencyOfAccount(
        string $accountId
    ): string {...}
}
```
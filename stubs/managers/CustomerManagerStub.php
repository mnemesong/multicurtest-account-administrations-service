<?php

namespace Pantagruel74\MulticurtestAccountAdministrationsServiceStubs\managers;

use Pantagruel74\MulticurtestAccountAdministrationsService\managers\CustomerManagerInterface;

class CustomerManagerStub implements CustomerManagerInterface
{
    const CUSTOMER_ID = "b92d21d2-1d12-44ea-b00d-83c185ecee34";

    public function isCustomerExists(string $customerId): bool
    {
        return $customerId === self::CUSTOMER_ID;
    }
}
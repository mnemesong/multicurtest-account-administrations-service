<?php

namespace Pantagruel74\MulticurtestAccountAdministrationsService\managers;

interface CustomerManagerInterface
{
    public function isCustomerExists(string $customerId): bool;
}
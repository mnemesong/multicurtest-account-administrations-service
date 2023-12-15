<?php

namespace Admin\MulticurtestAccountAdministrationsService\managers;

interface CustomerManagerInterface
{
    public function isCustomerExists(string $customerId): bool;
}
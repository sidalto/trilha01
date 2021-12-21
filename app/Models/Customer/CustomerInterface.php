<?php

namespace App\Models\Customer;

use App\Models\CustomerAccount\CustomerAccountInterface;

interface CustomerInterface
{
    /**
     * @param CustomerAccountInterface $customerAccountInterface
     */
    public function addAccount(CustomerAccountInterface $customerAccountInterface): void;

    /**
     * @return array
     */
    public function getAccounts(): array;

    /**
     * @return string
     */
    public function getAddress(): string;

    /**
     * @return string
     */
    public function getTelephone(): string;

    /**
     * @return string
     */
    public function getEmail(): string;

    /**
     * @return string
     */
    public function getPassword(): string;
}

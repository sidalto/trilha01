<?php

namespace App\Models\Customer;

use App\Models\CustomerAccount\CustomerAccountInterface;

interface CustomerInterface
{
    public function addAccount(CustomerAccountInterface $customerAccountInterface): void;

    public function getAddress(): string;

    public function getTelephone(): string;

    public function getEmail(): string;

    public function getPassword(): string;

    public function isAuthenticate(): bool;

    public function isCompany(): bool;
}

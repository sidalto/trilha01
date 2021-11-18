<?php

namespace App\Models\Customer;

interface CustomerInterface
{
    public function getAddress(): string;

    public function getTelephone(): string;

    public function getEmail(): string;

    public function getPassword(): string;

    public function isAuthenticate(): bool;

    public function isCompany(): bool;
}

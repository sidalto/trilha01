<?php

namespace App\Models\CustomerAccount;

interface CustomerAccountInterface
{
    public function getCurrentBalance(): float;

    public function setCurrentBalance(float $currentBalance): void;

    public function getNumber(): int;
}

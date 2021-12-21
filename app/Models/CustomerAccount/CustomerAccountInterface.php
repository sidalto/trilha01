<?php

namespace App\Models\CustomerAccount;

interface CustomerAccountInterface
{
    /**
     * @return float
     */
    public function getCurrentBalance(): float;

    /**
     * @param float $currentBalance
     */
    public function setCurrentBalance(float $currentBalance): void;

    /**
     * @return int
     */
    public function getNumber(): int;
}

<?php

namespace App\Models\CustomerAccount;

use DateTimeImmutable;

interface CustomerAccountInterface
{
    public function getCurrentBalance(): float;

    public function setCurrentBalance(float $currentBalance): void;

    public function getNumber(): int;

    // public function getReport(DateTimeImmutable $initialData, DateTimeImmutable $finalData): array;

    // public function withdraw(float $amount): bool;

    // public function deposit(float $amount): bool;

    // public function transfer(CustomerAccountInterface $account, float $amount): bool;

    // public function verifyAccount(CustomerAccountInterface $account): ?CustomerAccountInterface;
}

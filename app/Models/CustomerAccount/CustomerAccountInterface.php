<?php

namespace App\Models\CustomerAccount;

use DateTimeImmutable;

interface CustomerAccountInterface
{
    public function getCurrentBalance(): float;

    public function setCurrentBalance(float $currentBalance): void;

    public function getNumber(): string;

    public function getAccountReport(DateTimeImmutable $initialData, DateTimeImmutable $finalData): array;

    public function withdraw(float $amount): bool;

    public function deposit(float $amount): bool;

    public function transfer(Account $account, float $amount): bool;

    public function verifyAccount(Account $account): ?CustomerAccountInterface;
}

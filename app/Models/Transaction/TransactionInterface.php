<?php

namespace App\Models\Transaction;

use DateTimeImmutable;
use App\Models\CustomerAccount\CustomerAccountInterface;

interface TransactionInterface
{
    public function getReport(int $idAccount, DateTimeImmutable $initialData, DateTimeImmutable $finalData): array;

    public function withdraw(float $amount): bool;

    public function deposit(float $amount): bool;

    public function transfer(CustomerAccountInterface $accountSource, CustomerAccountInterface $accountDestination, float $amount): bool;

    public function payment(float $amount, string $description = ''): bool;
}

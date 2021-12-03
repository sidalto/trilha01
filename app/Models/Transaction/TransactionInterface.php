<?php

namespace App\Models\Transaction;

use DateTimeImmutable;
use App\Models\CustomerAccount\CustomerAccountInterface;

interface TransactionInterface
{
    public function getReport(CustomerAccountInterface $account, int $idAccount, DateTimeImmutable $initialData, DateTimeImmutable $finalData): array;

    public function withdraw(CustomerAccountInterface $accountSource, float $amount): bool;

    public function deposit(CustomerAccountInterface $account, float $amount): bool;

    public function transfer(CustomerAccountInterface $accountSource, CustomerAccountInterface $accountDestination, float $amount): bool;

    public function payment(CustomerAccountInterface $account, float $amount, string $description = ''): bool;
}

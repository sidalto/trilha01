<?php

namespace App\Models\Transaction;

use DateTimeImmutable;
use App\Models\CustomerAccount\CustomerAccountInterface;

interface TransactionInterface
{
    public function getReportByPeriod(int $account, string $initialData, string $finalData): array;

    public function withdraw(int $idCustomer, int $idAccount, float $amount, string $description = ''): bool;

    public function deposit(int $idCustomer, int $idAccount, float $amount, string $description = ''): bool;

    public function transfer(int $idCustomer, int $idSourceAccount, int $idDestinationAccount, float $amount, string $description = ''): bool;

    public function payment(int $idCustomer, int $idAccount, float $amount, string $description = ''): bool;
}

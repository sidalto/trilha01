<?php

namespace App\Models\Transaction;

interface TransactionInterface
{
    /**
     * @param int $account
     * @param string $initialDate
     */
    public function getReportByPeriod(int $account, string $initialDate, string $finalDate): array;

    /**
     * @param int $idCustomer
     * @param int $idAccount
     * @param float $amount
     * @param string $description
     * @return bool
     */
    public function withdraw(int $idCustomer, int $idAccount, float $amount, string $description = ''): bool;

    /**
     * @param int $idCustomer
     * @param int $idAccount
     * @param float $amount
     * @param string $description
     * @return bool
     */
    public function deposit(int $idCustomer, int $idAccount, float $amount, string $description = ''): bool;

    /**
     * @param int $idCustomer
     * @param int $idSourceAccount
     * @param int $idDestinationAccount
     * @param float $amount
     * @param string $description
     * @return bool
     */
    public function transfer(int $idCustomer, int $idSourceAccount, int $idDestinationAccount, float $amount, string $description = ''): bool;

    /**
     * @param int $idCustomer
     * @param int $idAccount
     * @param float $amount
     * @param string $description
     * @return bool
     */
    public function payment(int $idCustomer, int $idAccount, float $amount, string $description = ''): bool;
}

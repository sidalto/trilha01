<?php

namespace App\Repositories\TransactionRepository;

use App\Models\Transaction\TransactionInterface;

interface TransactionRepositoryInterface
{
    /**
     * @param int $idAccount
     * @return array
     */
    public function findAllByAccount(int $idAccount): array;

    /**
     * @param int $idAccount
     * @param string $initialDate
     * @param string $finalDate
     * @return array
     */
    public function findAllByDateInterval(int $idAccount, string $initialDate, string $finalDate): array;

    /**
     * @param TransactionInterface $transactionInterface
     * @return bool
     */
    public function save(TransactionInterface $transactionInterface): bool;

    /**
     * @param TransactionInterface $transactionInterface
     * @return bool
     */
    public function remove(TransactionInterface $transactionInterface): bool;
}

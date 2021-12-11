<?php

namespace App\Repositories\TransactionRepository;

use App\Models\Transaction\TransactionInterface;

interface TransactionRepositoryInterface
{
    public function findAllByAccount(int $idAccount): array;

    public function findAllByDateInterval(int $idAccount, string $initialDate, string $finalDate): array;

    public function save(TransactionInterface $transactionInterface): bool;

    public function remove(TransactionInterface $transactionInterface): bool;
}

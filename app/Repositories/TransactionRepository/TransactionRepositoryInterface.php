<?php

namespace App\Repositories\TransactionRepository;

use App\Models\Transaction\TransactionInterface;

interface TransactionRepositoryInterface
{
    public function findAllByCustomer(): array;

    public function findOneByDate(int $id): ?TransactionInterface;

    public function save(TransactionInterface $transactionInterface): bool;

    public function remove(TransactionInterface $transactionInterface): bool;
}

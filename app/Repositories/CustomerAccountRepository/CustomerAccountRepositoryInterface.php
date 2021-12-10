<?php

namespace App\Repositories\CustomerAccountRepository;

use App\Models\CustomerAccount\CustomerAccountInterface;

interface CustomerAccountRepositoryInterface
{
    public function findAllByCustomer(int $idCustomer): array;

    public function findOneByCustomer(int $idCustomer, int $idAccount): ?CustomerAccountInterface;

    public function findByAccountNumber(int $accountNumber): ?CustomerAccountInterface;

    public function save(CustomerAccountInterface $account, int $idCustomer): ?int;

    public function remove(CustomerAccountInterface $account): bool;
}

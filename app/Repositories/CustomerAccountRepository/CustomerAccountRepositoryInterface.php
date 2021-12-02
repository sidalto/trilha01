<?php

namespace App\Repositories\CustomerAccountRepository;

use DateTimeImmutable;
use App\Models\Customer\CustomerInterface;
use App\Models\CustomerAccount\CustomerAccountInterface;

interface CustomerAccountRepositoryInterface
{
    public function findAllByCustomer(int $idCustomer): array;

    public function findOneByCustomer(int $idCustomer, int $idAccount): ?CustomerAccountInterface;

    public function save(CustomerAccountInterface $account, int $idCustomer): bool;

    public function remove(CustomerAccountInterface $account): bool;
}

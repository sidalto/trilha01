<?php

namespace App\Repositories\CustomerAccountRepository;

use App\Models\CustomerAccount\CustomerAccountInterface;

interface CustomerAccountRepositoryInterface
{
    /**
     * @param int $idCustomer
     * @return array
     */
    public function findAllByCustomer(int $idCustomer): array;

    /**
     * @param int $idCustomer
     * @param int $idAccount
     * @return CustomerAccountInterface|null
     */
    public function findOneByCustomer(int $idCustomer, int $idAccount): ?CustomerAccountInterface;

    /**
     * @param int $accountNumber
     * @return CustomerAccountInterface|null
     */
    public function findByAccountNumber(int $accountNumber): ?CustomerAccountInterface;

    /**
     * @param CustomerAccountInterface $account
     * @param int $idCustomer
     * @return int|null
     */
    public function save(CustomerAccountInterface $account, int $idCustomer): ?int;

    /**
     * @param CustomerAccountInterface $account
     * @return bool
     */
    public function remove(CustomerAccountInterface $account): bool;
}

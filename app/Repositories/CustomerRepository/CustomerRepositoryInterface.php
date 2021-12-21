<?php

namespace App\Repositories\CustomerRepository;

use App\Models\Customer\CustomerInterface;

interface CustomerRepositoryInterface
{
    /**
     * @return array
     */
    public function findAll(): array;

    /**
     * @param int $id
     * @return CustomerInterface|null
     */
    public function findOne(int $id): ?CustomerInterface;

    /**
     * @param string $email
     * @return CustomerInterface|null
     */
    public function findByEmail(string $email): ?CustomerInterface;

    /**
     * @param CustomerInterface
     * @return int|null
     */
    public function save(CustomerInterface $customer): ?int;

    /**
     * @param CustomerInterface $customer
     * @return bool
     */
    public function remove(CustomerInterface $customer): bool;
}

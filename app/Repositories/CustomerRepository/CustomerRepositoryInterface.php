<?php

namespace App\Repositories\CustomerRepository;

use App\Models\Customer\CustomerInterface;

interface CustomerRepositoryInterface
{
    public function findAll(): array;

    public function findOne(int $id): ?CustomerInterface;

    public function save(CustomerInterface $customer): bool;

    public function remove(CustomerInterface $customer): bool;
}

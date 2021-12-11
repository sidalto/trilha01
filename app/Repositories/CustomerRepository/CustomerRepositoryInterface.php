<?php

namespace App\Repositories\CustomerRepository;

use App\Models\Customer\CustomerInterface;

interface CustomerRepositoryInterface
{
    public function findAll(): array;

    public function findOne(int $id): ?CustomerInterface;

    public function findByEmail(string $email): ?CustomerInterface;

    public function save(CustomerInterface $customer): ?int;

    public function remove(CustomerInterface $customer): bool;
}

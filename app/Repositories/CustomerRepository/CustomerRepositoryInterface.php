<?php

namespace App\Repositories\CustomerRepository;

use App\Models\Customer\CustomerInterface;
use PDOStatement;

interface CustomerRepositoryInterface
{
    public function getAll(): array;

    public function getById(CustomerInterface $customer): CustomerInterface;

    public function add(CustomerInterface $customer): CustomerInterface;

    public function edit(CustomerInterface $customer): CustomerInterface;

    public function remove(CustomerInterface $customer): bool;

    public function fillCustomer(PDOStatement $stmt): array;
}

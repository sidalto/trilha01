<?php

namespace App\Repositories\CustomerAccountRepository;

use App\Models\CustomerAccount\CustomerAccountInterface;

interface CustomerAccountRepositoryInterface
{
    public function getAll(): array;

    public function getById(): CustomerAccountInterface;

    public function add(CustomerAccountInterface $account): bool;

    public function edit(int $id): CustomerAccountInterface;

    public function remove(int $id): bool;
}

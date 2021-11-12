<?php

namespace App\Repositories\AccountRepository;

use App\Models\AccountInterface;

interface AccountRepositoryInterface
{
    public function getAll(): array;

    public function getById(): AccountInterface;

    public function add(AccountInterface $account): bool;

    public function edit(int $id): AccountInterface;

    public function remove(int $id): bool;
}

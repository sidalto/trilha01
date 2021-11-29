<?php

namespace App\Repositories\CustomerAccountRepository;

use DateTimeImmutable;
use App\Models\Customer\CustomerInterface;
use App\Models\CustomerAccount\CustomerAccountInterface;

interface CustomerAccountRepositoryInterface
{
    public function getCustomer(string $id): CustomerInterface;

    public function getByNumber(string $number): CustomerAccountInterface;

    public function getReport(DateTimeImmutable $initialData, DateTimeImmutable $finalData): array;

    public function save(CustomerAccountInterface $account): bool;

    public function remove(int $id): bool;
}

<?php

namespace App\Models\Account;

use App\Models\Account\AccountInterface;

use DateTimeImmutable;

class Account implements AccountInterface
{
    private int $id;
    private double $currentBalance;
    private int $typeAccount;
    private DateTimeImmutable $created_at;
    private ?string $description;
    private ?DateTimeImmutable $updated_at;
    private ?DateTimeImmutable $finished_at;

    public function __construct(
        double $currentBalance,
        int $typeAccount,
        DateTimeImmutable $created_at,
        ?string $description,
        ?DateTimeImmutable $updated_at,
        ?DateTimeImmutable $finished_at
    ) {
        $this->currentBalance = $currentBalance;
        $this->typeAccount = $typeAccount;
        $this->created_at = $created_at;
        $this->description = $description;
        $this->updated_at = $updated_at;
        $this->finished_at = $finished_at;
    }

    public function openAccount(): bool
    {
        return true;
    }

    public function finishAccount(): bool
    {
        return true;
    }

    public function getCurrentBalance(): double
    {
        return 1;
    }

    public function getAccountReport(): array
    {
        return [];
    }

    public function draft(double $amount): bool
    {
        return [];
    }

    public function deposit(double $amount): bool
    {
        return true;
    }

    public function transfer(int $sourceAccountId, int $destinationAccountId): bool
    {
        return true;
    }
}

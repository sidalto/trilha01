<?php

namespace App\Models\CustomerAccount;

use App\Models\CustomerAccount\CustomerAccountInterface;

use DateTimeImmutable;

class Account implements CustomerAccountInterface
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

    public function withdraw(double $amount): bool
    {
        return [];
    }

    public function deposit(double $amount): bool
    {
        return true;
    }

    public function transfer(int $sourceAccountId, int $destinationAccountId, double $amount): bool
    {
        return true;
    }
}

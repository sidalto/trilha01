<?php

namespace App\Models\Account;

interface AccountInterface
{
    public function openAccount(): bool;

    public function finishAccount(): bool;

    public function getCurrentBalance(): double;

    public function getAccountReport(): array;

    public function draft(double $amount): bool;

    public function deposit(double $amount): bool;

    public function transfer(int $sourceAccountId, int $destinationAccountId): bool;
}

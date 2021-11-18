<?php

namespace App\Models\CustomerAccount;

interface CustomerAccountInterface
{
    public function openAccount(): bool;

    public function finishAccount(): bool;

    public function getCurrentBalance(): double;

    public function getAccountReport(): array;

    public function withdraw(double $amount): bool;

    public function deposit(double $amount): bool;

    public function transfer(int $sourceAccountId, int $destinationAccountId, double $amount): bool;
}

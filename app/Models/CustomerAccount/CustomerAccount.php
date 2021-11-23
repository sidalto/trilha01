<?php

namespace App\Models\CustomerAccount;

use App\Models\CustomerAccount\CustomerAccountInterface;

use DateTimeImmutable;
use Exception;

class Account implements CustomerAccountInterface
{
    private int $id;
    private string $number;
    private float $currentBalance;
    private int $typeAccount;
    private DateTimeImmutable $created_at;
    private ?string $description;
    private ?DateTimeImmutable $updated_at;
    private ?DateTimeImmutable $finished_at;

    public function __construct(
        string $number,
        float $currentBalance,
        int $typeAccount,
        DateTimeImmutable $created_at,
        ?string $description,
        ?DateTimeImmutable $updated_at,
        ?DateTimeImmutable $finished_at
    ) {
        $this->number = $number;
        $this->currentBalance = $currentBalance;
        $this->typeAccount = $typeAccount;
        $this->created_at = $created_at;
        $this->description = $description;
        $this->updated_at = $updated_at;
        $this->finished_at = $finished_at;
    }

    public function getNumber(): string
    {
        return $this->number;
    }

    public function getCurrentBalance(): float
    {
        return $this->currentBalance;
    }

    public function setCurrentBalance(float $currentBalance): void
    {
        $this->currentBalance = $currentBalance;
    }

    private function generateNumber(): string
    {
        $number = new DateTimeImmutable('now');

        return $number->getTimestamp();
    }

    public function getAccountReport(DateTimeImmutable $initialData, DateTimeImmutable $finalData): array
    {
        if ($initialData > $finalData) {
            throw new Exception('Invalid date interval');
        }

        $accountReport = $this->accountRepositoryInterface->getAccountReport($initialData, $finalData);

        return $accountReport;
    }

    public function withdraw(float $amount): bool
    {
        if ($this->getCurrentBalance() <= 0 || $this->getCurrentBalance() < $amount) {
            throw new Exception('Insufficient founds');
        }

        $this->setCurrentBalance($this->getCurrentBalance() - $amount);

        return true;
    }

    public function deposit(float $amount): bool
    {
        if ($amount <= 0) {
            throw new Exception('Invalid amount from deposit');
        }

        $this->setCurrentBalance($this->getCurrentBalance() + $amount);

        return true;
    }

    public function transfer(CustomerAccountInterface $destinationAccount, float $amount): bool
    {
        $destinationAccount = $this->verifyAccount($destinationAccount);

        if (!$amount > 0) {
            throw new Exception('Invalid amount from transfer');
        }

        if (!$destinationAccount) {
            throw new Exception('Invalid destination account from transfer');
        }

        $destinationAccount->setCurrentBalance($destinationAccount->getCurrentBalance() + $amount);

        $this->setCurrentBalance($this->getCurrentBalance() - $amount);

        return true;
    }

    public function verifyAccount(CustomerAccountInterface $account): ?CustomerAccountInterface
    {
        return $this->accountRepositoryInterface->getById($account);
    }
}

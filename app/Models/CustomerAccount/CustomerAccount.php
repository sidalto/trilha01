<?php

namespace App\Models\CustomerAccount;

use DateTimeImmutable;
use Exception;
use App\Models\CustomerAccount\CustomerAccountInterface;

class CustomerAccount implements CustomerAccountInterface
{
    private int $id;
    private int $number;
    private float $currentBalance;
    private int $typeAccount;
    private string $description;
    private ?DateTimeImmutable $created_at;
    private ?DateTimeImmutable $updated_at;

    public function fill(
        float $currentBalance,
        int $typeAccount,
        ?string $description,
        ?int $number,
        int $id = 0,
        ?DateTimeImmutable $created_at = null,
        ?DateTimeImmutable $updated_at = null
    ) {
        $this->id = $id;
        $this->currentBalance = $currentBalance;
        $this->typeAccount = $typeAccount;
        $this->created_at = $created_at;
        $this->description = $description;
        $this->updated_at = $updated_at;
        $this->verifyNumber($number);
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getNumber(): int
    {
        return $this->number;
    }

    private function setNumber(int $number): void
    {
        $this->number = $number;
    }

    private function generateNumber(): int
    {
        $number = new DateTimeImmutable('now');
        $number = $number->getTimestamp();

        return $number;
    }

    private function verifyNumber(?int $number): void
    {
        if (empty($number)) {
            $newNumber = $this->generateNumber();
        }

        $this->setNumber($number);
    }

    public function getCurrentBalance(): float
    {
        return $this->currentBalance;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getType(): float
    {
        return $this->type;
    }

    public function setCurrentBalance(float $currentBalance): void
    {
        $this->currentBalance = $currentBalance;
    }

    // public function getReport(DateTimeImmutable $initialData, DateTimeImmutable $finalData): array
    // {
    //     if ($initialData > $finalData) {
    //         throw new Exception('Invalid date interval');
    //     }

    //     $accountReport = $this->accountRepositoryInterface->getAccountReport($initialData, $finalData);

    //     return $accountReport;
    // }

    // public function withdraw(float $amount): bool
    // {
    //     if ($this->getCurrentBalance() <= 0 || $this->getCurrentBalance() < $amount) {
    //         throw new Exception('Insufficient founds');
    //     }

    //     $this->setCurrentBalance($this->getCurrentBalance() - $amount);

    //     return true;
    // }

    // public function deposit(float $amount): bool
    // {
    //     if ($amount <= 0) {
    //         throw new Exception('Invalid amount from deposit');
    //     }

    //     $this->setCurrentBalance($this->getCurrentBalance() + $amount);

    //     return true;
    // }

    // public function transfer(CustomerAccountInterface $destinationAccount, float $amount): bool
    // {
    //     $destinationAccount = $this->verifyAccount($destinationAccount);

    //     if (!$amount > 0) {
    //         throw new Exception('Invalid amount from transfer');
    //     }

    //     if (!$destinationAccount) {
    //         throw new Exception('Invalid destination account from transfer');
    //     }

    //     $destinationAccount->setCurrentBalance($destinationAccount->getCurrentBalance() + $amount);

    //     $this->setCurrentBalance($this->getCurrentBalance() - $amount);

    //     return true;
    // }

    // public function verifyAccount(CustomerAccountInterface $account): ?CustomerAccountInterface
    // {
    //     return $this->accountRepositoryInterface->getById($account);
    // }
}

<?php

namespace App\Models\CustomerAccount;

use DateTimeImmutable;
use App\Models\CustomerAccount\CustomerAccountInterface;

class CustomerAccount implements CustomerAccountInterface
{
    private int $id;
    private int $number;
    private float $currentBalance;
    private int $typeAccount;
    private ?string $description;
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
        $this->currentBalance = $currentBalance;
        $this->typeAccount = $typeAccount;
        $this->description = $description;
        $this->number = $number;
        $this->id = $id;
        $this->created_at = $created_at;
        $this->updated_at = $updated_at;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getNumber(): int
    {
        if (empty($this->number)) {
            $newNumber = $this->generateNumber();
            $this->setNumber($newNumber);
        }

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

    public function getCurrentBalance(): float
    {
        return $this->currentBalance;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function getTypeAccount(): int
    {
        return $this->typeAccount;
    }

    public function setCurrentBalance(float $currentBalance): void
    {
        $this->currentBalance = $currentBalance;
    }
}

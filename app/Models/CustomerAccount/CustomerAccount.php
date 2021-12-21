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

    /**
     * @param float $currentBalance
     * @param int $typeAccount
     * @param string|null $description
     * @param int|null $number
     * @param int
     * @param DateTimeImmutable|null $created_at
     * @param DateTimeImmutable|null $updated_at
     */
    public function fill(
        float $currentBalance,
        int $typeAccount,
        ?string $description,
        ?int $number,
        int $id = 0,
        ?DateTimeImmutable $created_at = null,
        ?DateTimeImmutable $updated_at = null
    ): void {
        $this->currentBalance = $currentBalance;
        $this->typeAccount = $typeAccount;
        $this->description = $description;
        $this->number = $number;
        $this->id = $id;
        $this->created_at = $created_at;
        $this->updated_at = $updated_at;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return int
     */
    public function getNumber(): int
    {
        if (empty($this->number)) {
            $newNumber = $this->generateNumber();
            $this->setNumber($newNumber);
        }

        return $this->number;
    }

    /**
     * @param int $number
     */
    private function setNumber(int $number): void
    {
        $this->number = $number;
    }

    /**
     * @return int
     */
    private function generateNumber(): int
    {
        $number = new DateTimeImmutable('now');
        $number = $number->getTimestamp();

        return $number;
    }

    /**
     * @return float
     */
    public function getCurrentBalance(): float
    {
        return $this->currentBalance;
    }

    /**
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @return int
     */
    public function getTypeAccount(): int
    {
        return $this->typeAccount;
    }

    /**
     * @param float $currentBalance
     */
    public function setCurrentBalance(float $currentBalance): void
    {
        $this->currentBalance = $currentBalance;
    }
}

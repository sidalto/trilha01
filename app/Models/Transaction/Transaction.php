<?php

namespace App\Models\Transaction;

use Exception;
use DateTimeImmutable;
use App\Models\Transaction\TransactionInterface;
use App\Models\CustomerAccount\CustomerAccountInterface;

class Transaction implements TransactionInterface
{
    private int $id;
    private int $account_id;
    private int $type;
    private float $amount;
    private string $description;
    private ?DateTimeImmutable $created_at;
    private ?DateTimeImmutable $updated_at;

    public function fill(
        float $amount,
        int $type,
        ?string $description,
        int $account_id,
        int $id = 0,
        ?DateTimeImmutable $created_at = null,
        ?DateTimeImmutable $updated_at = null
    ) {
        $this->amount = $amount;
        $this->type = $type;
        $this->description = $description;
        $this->account_id = $account_id;
        $this->id = $id;
        $this->created_at = $created_at;
        $this->updated_at = $updated_at;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getAmount(): float
    {
        return $this->amount;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getCurrentBalance(): float
    {
        return $this->currentBalance;
    }

    public function getType(): float
    {
        return $this->type;
    }

    public function getReport(CustomerAccountInterface $account, int $idAccount, DateTimeImmutable $initialData, DateTimeImmutable $finalData): array
    {
        if ($initialData > $finalData) {
            throw new Exception('Invalid date interval');
        }

        $transactionReport = $this->transactionRepositoryInterface->getTransactionReport($idAccount, $initialData, $finalData);

        return $transactionReport;
    }

    public function withdraw(CustomerAccountInterface $account, float $amount): bool
    {
        if ($this->account->getCurrentBalance() <= 0 || $this->account->getCurrentBalance() < $amount) {
            throw new Exception('Insufficient founds');
        }
        $this->account->setCurrentBalance($this->account->getCurrentBalance() - $amount);

        return true;
    }

    public function deposit(CustomerAccountInterface $account, float $amount): bool
    {
        if ($amount <= 0) {
            throw new Exception('Invalid amount from deposit');
        }
        $this->account->setCurrentBalance($this->account->getCurrentBalance() + $amount);

        return true;
    }

    public function transfer(CustomerAccountInterface $sourceAccount, CustomerAccountInterface $destinationAccount, float $amount): bool
    {
        $destinationAccount = $this->verifyAccount($destinationAccount);

        if (!$amount > 0) {
            throw new Exception('Invalid amount from transfer');
        }

        if (!$destinationAccount) {
            throw new Exception('Invalid destination account from transfer');
        }

        $destinationAccount->setCurrentBalance($destinationAccount->getCurrentBalance() + $amount);

        $this->sourceAccount->setCurrentBalance($this->getCurrentBalance() - $amount);

        return true;
    }

    public function payment(CustomerAccountInterface $account, float $amount, string $description = ''): bool
    {
        if (!$amount > 0) {
            throw new Exception('Invalid amount from payment');
        }

        if ($this->account->getCurrentBalance() < $amount) {
            throw new Exception('Insufficient founds');
        }

        $this->account->setCurrentBalance($this->account->getCurrentBalance() - $amount);

        return true;
    }

    public function verifyAccount(CustomerAccountInterface $account): ?CustomerAccountInterface
    {
        return $this->accountRepositoryInterface->getById($account);
    }
}

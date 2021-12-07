<?php

namespace App\Models\Transaction;

use Exception;
use DateTimeImmutable;
use App\Database\Connection;
use App\Models\Transaction\TransactionInterface;
use App\Models\CustomerAccount\CustomerAccountInterface;
use App\Repositories\TransactionRepository\TransactionRepository;
use App\Repositories\CustomerAccountRepository\CustomerAccountRepository;

class Transaction implements TransactionInterface
{
    private int $type;
    private int $id;
    private int $account_id;
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

    public function getType(): float
    {
        return $this->type;
    }

    public function getCreatedAt(): ?DateTimeImmutable
    {
        return $this->created_at;
    }

    public function getAccountId(): int
    {
        return $this->account_id;
    }

    public function getReportByPeriod(int $idAccount, string $initialDate, string $finalDate): array
    {
        if ($initialDate > $finalDate) {
            throw new Exception('Invalid date interval');
        }

        $transactionRepository = new TransactionRepository(Connection::getInstance());
        $transactions = $transactionRepository->findAllByDateInterval($idAccount, $initialDate, $finalDate);

        return $transactions;
    }

    public function withdraw(int $idAccount, float $amount, string $description = ''): bool
    {
        $accountRepository = new CustomerAccountRepository(Connection::getInstance());
        $account = $accountRepository->findOne($idAccount);

        if (!$account) {
            throw new Exception('Invalid account');
        }

        if ($account->getCurrentBalance() <= 0 || $account->getCurrentBalance() < $amount) {
            throw new Exception('Insufficient founds');
        }

        $account->setCurrentBalance($account->getCurrentBalance() - $amount);
        $result = $this->accountRepositoryInterface->save($account);

        if (!$result) {
            throw new Exception('Withdraw error');
        }

        $this->fill($amount, 1, $description, $account->getId());
        $transactionStatus = $this->transactionRepositoryInterface->save($this);

        return $transactionStatus;
    }

    public function deposit(int $idAccount, float $amount, string $description = ''): bool
    {
        $account = $this->accountRepositoryInterface->findOne($idAccount);

        if (!$account) {
            throw new Exception('Invalid account');
        }

        if ($amount <= 0) {
            throw new Exception('Invalid amount from deposit');
        }

        $account->setCurrentBalance($account->getCurrentBalance() + $amount);
        $result = $this->accountRepositoryInterface->save($account);

        if (!$result) {
            throw new Exception('Deposit error');
        }

        $this->fill($amount, 1, $description, $account->getId());
        $transactionStatus = $this->transactionRepositoryInterface->save($this);

        return $transactionStatus;
    }

    public function transfer(int $idSourceAccount, int $destinationAccountNumber, float $amount, string $description = ''): bool
    {
        $sourceAccount = $this->accountRepositoryInterface->findOne($idSourceAccount);
        $destinationAccount = $this->accountRepositoryInterface->findByAccountNumber($destinationAccountNumber);

        if (!$sourceAccount) {
            throw new Exception('Invalid source account');
        }

        if (!$destinationAccount) {
            throw new Exception('Invalid destination account');
        }

        if (!$amount > 0) {
            throw new Exception('Invalid amount from transfer');
        }

        $destinationAccount->setCurrentBalance($destinationAccount->getCurrentBalance() + $amount);
        $result = $this->accountRepositoryInterface->save($destinationAccount);

        if (!$result) {
            throw new Exception('Withdraw error');
        }

        $sourceAccount->setCurrentBalance($sourceAccount->getCurrentBalance() - $amount);
        $result = $this->accountRepositoryInterface->save($sourceAccount);

        if (!$result) {
            throw new Exception('Withdraw error');
        }

        $this->fill($amount, 1, $description, $destinationAccount->getId());
        $destinationTransaction = $this->transactionRepositoryInterface->save($this);

        $this->fill($amount, 1, $description, $sourceAccount->getId());
        $sourceTransaction = $this->transactionRepositoryInterface->save($this);


        return $destinationTransaction;
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

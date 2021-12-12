<?php

namespace App\Models\Transaction;

use Exception;
use Monolog\Logger;
use DateTimeImmutable;
use App\Database\Connection;
use Monolog\Handler\StreamHandler;
use App\Models\Transaction\TransactionInterface;
use App\Repositories\TransactionRepository\TransactionRepository;
use App\Repositories\CustomerAccountRepository\CustomerAccountRepository;

class Transaction implements TransactionInterface
{
    private array $typeTransaction = [
        'DEPOSITO' => 1,
        'SAQUE' => 2,
        'TRANSFERENCIA' => 3,
        'PAGAMENTO' => 4
    ];
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

    private function logger(): Logger
    {
        $handler = new StreamHandler(__DIR__ . '/../../Logs/system.log', Logger::DEBUG);
        $logger = new Logger('wjcrypto-log');
        $logger->pushHandler($handler);

        return $logger;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getAmount(): float
    {
        return $this->amount;
    }

    public function setAmount(float $amount): void
    {
        $this->amount = $amount;
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

    /**
     * @param int $idAccount
     * @param string $initialDate
     * @param string $finalDate
     * @return array
     */
    public function getReportByPeriod(int $idAccount, string $initialDate, string $finalDate): array
    {
        try {
            if ($initialDate > $finalDate) {
                throw new Exception('Intervalo de datas inválido');
            }

            $transactionRepository = new TransactionRepository(Connection::getInstance());
            $transactions = $transactionRepository->findAllByDateInterval($idAccount, $initialDate, $finalDate);

            $this->logger()->info("Extrato efetuado na conta de ID: " . $idAccount);

            return $transactions;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * @param int $idCustomer
     * @param int $idAccount
     * @param float $amount
     * @param string $description
     * @return bool
     */
    public function withdraw(int $idCustomer, int $idAccount, float $amount, string $description = ''): bool
    {
        try {
            Connection::getInstance()->beginTransaction();
            $transactionRepository = new TransactionRepository(Connection::getInstance());
            $accountRepository = new CustomerAccountRepository(Connection::getInstance());
            $account = $accountRepository->findOneByCustomer($idAccount, $idCustomer);

            if (!$account) {
                throw new Exception('Conta inválida');
            }

            if ($amount <= 0) {
                throw new Exception('Valor inválido para depósito');
            }

            if ($account->getCurrentBalance() <= 0 || $account->getCurrentBalance() < $amount) {
                throw new Exception('Saldo insuficiente');
            }

            $account->setCurrentBalance($account->getCurrentBalance() - $amount);
            $result = $accountRepository->save($account, $idCustomer);

            if (!$result) {
                throw new Exception('Erro ao processar o saque');
            }

            $this->fill($amount, $this->typeTransaction['SAQUE'], $description, $account->getId());
            $transactionStatus = $transactionRepository->save($this);
            Connection::getInstance()->commit();

            $this->logger()->info("Saque efetuado na conta de ID: " . $idAccount);

            return $transactionStatus;
        } catch (Exception $e) {
            Connection::getInstance()->rollBack();
            throw $e;
        }
    }

    /**
     * @param int $idCustomer
     * @param int $idAccount
     * @param float $amount
     * @param string $description
     * @return bool
     */
    public function deposit(int $idCustomer, int $idAccount, float $amount, string $description = ''): bool
    {
        try {
            Connection::getInstance()->beginTransaction();
            $transactionRepository = new TransactionRepository(Connection::getInstance());
            $accountRepository = new CustomerAccountRepository(Connection::getInstance());
            $account = $accountRepository->findOneByCustomer($idAccount, $idCustomer);

            if (!$account) {
                throw new Exception('Conta inválida');
            }

            if ($amount <= 0) {
                throw new Exception('Valor inválido para depósito');
            }

            $account->setCurrentBalance($account->getCurrentBalance() + $amount);
            $result = $accountRepository->save($account, $idCustomer);

            if (!$result) {
                throw new Exception('Erro ao processar o depósito');
            }

            $this->fill($amount, $this->typeTransaction['DEPOSITO'], $description, $account->getId());
            $transactionStatus = $transactionRepository->save($this);
            Connection::getInstance()->commit();

            $this->logger()->info("Depósito efetuado na conta de ID: " . $idAccount);

            return $transactionStatus;
        } catch (Exception $e) {
            var_dump($e);
            Connection::getInstance()->rollBack();
            throw $e;
        }
    }

    /**
     * @param int $idCustomer
     * @param int $idSourceAccount
     * @param int $destinationAccountNumber
     * @param float $amount
     * @param string $description
     * @return bool
     */
    public function transfer(int $idCustomer, int $idSourceAccount, int $destinationAccountNumber, float $amount, string $description = ''): bool
    {
        try {
            Connection::getInstance()->beginTransaction();
            $transactionRepository = new TransactionRepository(Connection::getInstance());
            $accountRepository = new CustomerAccountRepository(Connection::getInstance());
            $sourceAccount = $accountRepository->findOneByCustomer($idSourceAccount, $idCustomer);
            $destinationAccount = $accountRepository->findByAccountNumber($destinationAccountNumber);

            if (!$sourceAccount || !$destinationAccount) {
                throw new Exception('Conta incorreta para transferência');
            }

            if ($sourceAccount->getCurrentBalance() < $amount) {
                throw new Exception('Saldo insuficiente');
            }

            if (!$amount > 0) {
                throw new Exception('Valor incorreto para transferência');
            }

            $destinationAccount->setCurrentBalance($destinationAccount->getCurrentBalance() + $amount);
            $result = $accountRepository->save($destinationAccount, $idCustomer);

            if (!$result) {
                throw new Exception('Erro ao realizar a transferência');
            }

            $this->fill($amount, $this->typeTransaction['TRANSFERENCIA'], $description, $destinationAccount->getId());
            $transactionRepository->save($this);

            $sourceAccount->setCurrentBalance($sourceAccount->getCurrentBalance() - $amount);
            $result = $accountRepository->save($sourceAccount, $idCustomer);

            if (!$result) {
                throw new Exception('Withdraw error');
            }

            $this->fill($amount, $this->typeTransaction['TRANSFERENCIA'], $description, $sourceAccount->getId());
            $transactionRepository->save($this);
            Connection::getInstance()->commit();

            $this->logger()->info("Transferência efetuada da conta ID: " . $sourceAccount . " para a conta ID: " . $destinationAccount);

            return $result;
        } catch (Exception $e) {
            Connection::getInstance()->rollBack();
            throw $e;
        }
    }

    /**
     * @param int $idCustomer
     * @param int $idAccount
     * @param float $amount
     * @param string $description
     * @return bool
     */
    public function payment(int $idCustomer, int $idAccount, float $amount, string $description = ''): bool
    {
        try {
            Connection::getInstance()->beginTransaction();
            $transactionRepository = new TransactionRepository(Connection::getInstance());
            $accountRepository = new CustomerAccountRepository(Connection::getInstance());
            $account = $accountRepository->findOneByCustomer($idAccount, $idCustomer);

            if (!$account) {
                throw new Exception('Conta inválida');
            }

            if ($account->getCurrentBalance() <= 0 || $account->getCurrentBalance() < $amount) {
                throw new Exception('Saldo insuficiente');
            }

            $account->setCurrentBalance($account->getCurrentBalance() - $amount);
            $result = $accountRepository->save($account, $idCustomer);

            if (!$result) {
                throw new Exception('Erro ao processar o pagamento');
            }

            $this->fill($amount, $this->typeTransaction['PAGAMENTO'], $description, $account->getId());
            $transactionStatus = $transactionRepository->save($this);
            Connection::getInstance()->commit();

            $this->logger()->info("Pagamento efetuado na conta de ID: " . $idAccount);

            return $transactionStatus;
        } catch (Exception $e) {
            Connection::getInstance()->rollBack();
            throw $e;
        }
    }
}

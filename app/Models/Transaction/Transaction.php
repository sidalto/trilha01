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

    /**
     * @param float $amount
     * @param int $type
     * @param string|null $description
     * @param int $account_id
     * @param int $id
     * @param DateTimeImmutable|null $created_at
     * @param DateTimeImmutable|null $updated_at
     */
    public function fill(
        float $amount,
        int $type,
        ?string $description,
        int $account_id,
        int $id = 0,
        ?DateTimeImmutable $created_at = null,
        ?DateTimeImmutable $updated_at = null
    ): void {
        $this->amount = $amount;
        $this->type = $type;
        $this->description = $description;
        $this->account_id = $account_id;
        $this->id = $id;
        $this->created_at = $created_at;
        $this->updated_at = $updated_at;
    }

    /**
     * @return Logger
     */
    private function logger(): Logger
    {
        $handler = new StreamHandler(__DIR__ . '/../../Logs/system.log', Logger::DEBUG);
        $logger = new Logger('wjcrypto-log');
        $logger->pushHandler($handler);

        return $logger;
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
     * @return float
     */
    public function getAmount(): float
    {
        return $this->amount;
    }

    /**
     * @param float $amount
     */
    public function setAmount(float $amount): void
    {
        $this->amount = $amount;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @return float
     */
    public function getType(): float
    {
        return $this->type;
    }

    /**
     * @return DateTimeImmutable|null
     */
    public function getCreatedAt(): ?DateTimeImmutable
    {
        return $this->created_at;
    }

    /**
     * @return int
     */
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
                throw new Exception('Intervalo de datas inv??lido');
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
                throw new Exception('Conta inv??lida');
            }

            if ($amount <= 0) {
                throw new Exception('Valor inv??lido para dep??sito');
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
                throw new Exception('Conta inv??lida');
            }

            if ($amount <= 0) {
                throw new Exception('Valor inv??lido para dep??sito');
            }

            $account->setCurrentBalance($account->getCurrentBalance() + $amount);
            $result = $accountRepository->save($account, $idCustomer);

            if (!$result) {
                throw new Exception('Erro ao processar o dep??sito');
            }

            $this->fill($amount, $this->typeTransaction['DEPOSITO'], $description, $account->getId());
            $transactionStatus = $transactionRepository->save($this);
            Connection::getInstance()->commit();

            $this->logger()->info("Dep??sito efetuado na conta de ID: " . $idAccount);

            return $transactionStatus;
        } catch (Exception $e) {
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
                throw new Exception('Conta incorreta para transfer??ncia');
            }

            if ($sourceAccount->getCurrentBalance() < $amount) {
                throw new Exception('Saldo insuficiente');
            }

            if (!$amount > 0) {
                throw new Exception('Valor incorreto para transfer??ncia');
            }

            $destinationAccount->setCurrentBalance($destinationAccount->getCurrentBalance() + $amount);
            $result = $accountRepository->save($destinationAccount, $idCustomer);

            if (!$result) {
                throw new Exception('Erro ao realizar a transfer??ncia');
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

            $this->logger()->info("Transfer??ncia efetuada da conta ID: " . $idSourceAccount . " para a conta ID: " . $destinationAccount->getId());

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
                throw new Exception('Conta inv??lida');
            }

            if (!$amount > 0) {
                throw new Exception('Valor incorreto para pagamento');
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

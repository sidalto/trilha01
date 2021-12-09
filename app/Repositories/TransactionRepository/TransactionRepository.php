<?php

namespace App\Repositories\TransactionRepository;

use PDO;
use Exception;
use PDOException;
use PDOStatement;
use DateTimeImmutable;
use App\Models\Transaction\Transaction;
use App\Models\Customer\CustomerInterface;
use App\Repositories\Traits\PrepareDatabaseSql;
use App\Models\Transaction\TransactionInterface;
use App\Models\CustomerAccount\CustomerAccountInterface;
use App\Repositories\TransactionRepository\TransactionRepositoryInterface;

class TransactionRepository implements TransactionRepositoryInterface
{
    use PrepareDatabaseSql;

    private CustomerAccountInterface $account;
    private TransactionInterface $transaction;
    private CustomerInterface $customer;

    /**
     *
     * @param PDO $connection
     */
    public function __construct(PDO $connection)
    {
        self::$connection = $connection;
    }

    /**
     * Fill the CustomerInterface object
     *
     * @param PDOStatement $stmt
     * @return array
     */
    public function fillTransaction(PDOStatement $stmt): array
    {
        try {
            $stmt->execute();
            $transactionList = [];

            while ($transactionData = $stmt->fetch()) {
                $transaction = new Transaction();
                $transaction->fill(
                    $transactionData['amount'],
                    $transactionData['type'],
                    $transactionData['description'] ?: '',
                    $transactionData['account_id'],
                    $transactionData['id'],
                    new DateTimeImmutable($transactionData['created_at']),
                    $transactionData['updated_at'] ? new DateTimeImmutable($transactionData['updated_at']) : NULL
                );

                $transactionList[$transactionData['id']] = $transaction;
            }

            return $transactionList;
        } catch (PDOException $e) {
            throw new PDOException($e);
        }
    }

    /**
     *
     * @param int $idAccount
     * @return array
     */
    public function findAllByAccount(int $idAccount): array
    {
        try {
            $sql = "SELECT t.id, c.id as customer_id, ca.id as account_id, ca.number as account_number, t.amount, t.description, t.type, t.created_at, t.updated_at FROM customers as c INNER JOIN customers_accounts as ca ON (c.id = ca.customers_id) INNER JOIN transactions as t ON (t.account_id = ca.id) AND ca.id = :idAccount";

            $params = ['idAccount' => $idAccount];

            $stmt = $this->prepareBind($sql, $params);

            return $this->fillTransaction($stmt);
        } catch (PDOException $e) {
            throw new PDOException($e);
        }
    }

    /**
     *
     * @param int $idAccount
     * @param string $initialDate
     * @param string $finalDate
     * @return array
     */
    public function findAllByDateInterval(int $idAccount, string $initialDate, string $finalDate): array
    {
        try {
            $sql = "SELECT t.id, t.account_id, t.amount, t.description, t.type, t.created_at, t.updated_at, ca.number as account_number FROM transactions as t JOIN customers_accounts as ca ON (t.account_id = ca.id) AND ca.id = :idAccount AND t.created_at BETWEEN :initialDate AND :finalDate ORDER BY t.created_at DESC";

            $initialDate = $initialDate . " 00:00:00";
            $finalDate = $finalDate . " 23:59:59";

            $params = [
                'idAccount' => $idAccount,
                'initialDate' => $initialDate,
                'finalDate' => $finalDate,
            ];

            $stmt = $this->prepareBind($sql, $params);
            $stmt->execute();
            $transactions = $this->fillTransaction($stmt);

            return $transactions;
        } catch (PDOException $e) {
            throw new PDOException($e);
        }
    }

    /**
     *
     * @param TransactionInterface $transaction
     * @return bool
     */
    public function save(TransactionInterface $transaction): bool
    {
        try {
            if (!$transaction->getId()) {
                return $this->insert($transaction, $transaction->getAccountId());
            }

            return $this->update($transaction);
        } catch (Exception $e) {
            throw new Exception($e);
        }
    }

    /**
     *            
     * @param TransactionInterface $transaction
     * @param int $idAccount
     * @return bool
     */
    private function insert(TransactionInterface $transaction, int $idAccount): bool
    {
        try {
            $sql = "INSERT INTO transactions (amount, description, type, account_id) VALUES (:amount, :description, :type, :account_id)";

            $params = [
                'amount' => $transaction->getAmount(),
                'description' => $transaction->getDescription(),
                'type' => $transaction->getType(),
                'account_id' => $idAccount
            ];

            $stmt = $this->prepareBind($sql, $params);
            $result = $stmt->execute();

            if ($result) {
                $transaction->setId($this->getInsertId());
            }

            return $result;
        } catch (Exception $e) {
            throw new Exception("Erro ao salvar a transação");
        }
    }

    /**
     *
     * @param TransactionInterface $transaction
     * @return bool
     */
    private function update(TransactionInterface $transaction): bool
    {
        try {
            $sql = "UPDATE transactions SET company_name = :company_name, cnpj = :cnpj, state_registration = :state_registration, foundation_date = :foundation_date,  address = :address, telephone = :telephone, email = :email, password = :password WHERE id = :id";

            $params = [
                'company_name' => $transaction->getCompanyName(),
                'cnpj' => $transaction->getCnpj(),
                'state_registration' => $transaction->getStateRegistration(),
                'foundation_date' => $transaction->getFoundationDate()->format('Y-m-d'),
                'address' => $transaction->getAddress(),
                'telephone' => $transaction->getTelephone(),
                'email' => $transaction->getEmail(),
                'password' => password_hash($transaction->getPassword(), PASSWORD_DEFAULT),
                'id' => $transaction->getId()
            ];

            $stmt = $this->prepareBind($sql, $params);

            return $stmt->execute();
        } catch (Exception $e) {
            throw new Exception("Erro ao atualizar a transação");
        }
    }

    /**
     *
     * @param TransactionInterface $transaction
     * @return bool
     */
    public function remove(TransactionInterface $transaction): bool
    {
        try {
            $sql = "DELETE FROM transactions WHERE id = :id";

            $params = ['id' => $transaction->getId()];
            $stmt = $this->prepareBind($sql, $params);

            return $stmt->execute();
        } catch (Exception $e) {
            throw new Exception("Erro ao remover a transação");
        }
    }
}

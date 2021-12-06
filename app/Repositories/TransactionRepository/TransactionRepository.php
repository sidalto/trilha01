<?php

namespace App\Repositories\TransactionRepository;

use PDO;
use Exception;
use PDOStatement;
use DateTimeImmutable;
use App\Models\Transaction\Transaction;
use App\Models\Customer\CustomerInterface;
use App\Models\CustomerAccount\CustomerAccount;
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
     * @param CustomerInterface $customer
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
                    $transactionData['description'],
                    $transactionData['account_id'],
                    $transactionData['id'],
                    new DateTimeImmutable($transactionData['created_at']),
                    $transactionData['updated_at'] ? new DateTimeImmutable($transactionData['updated_at']) : NULL
                );

                $transactionList[$transactionData['id']] = $transaction;
            }

            return $transactionList;
        } catch (Exception $e) {
            // throw new Exception("Not possible execute the query");
            throw new Exception($e->getMessage());
        }
    }

    /**
     *
     * @return array
     */
    public function findAllByAccount(int $idAccount): array
    {
        try {
            $sql = "SELECT t.id, c.id as customer_id, ca.id as account_id, ca.number as account_number, t.amount, t.description, t.type, t.created_at, t.updated_at FROM customers as c INNER JOIN customers_accounts as ca ON (c.id = ca.customers_id) INNER JOIN transactions as t ON (t.account_id = ca.id) AND ca.id = :idAccount";

            $params = ['idAccount' => $idAccount];

            $stmt = $this->prepareBind($sql, $params);

            return $this->fillTransaction($stmt);
        } catch (Exception $e) {
            // throw new Exception("Not possible execute the query");
            throw new Exception($e->getMessage());
        }
    }

    /**
     *
     * @param int $idCustomer
     * @param int $idAccount
     * @return array
     */
    public function findAllByDateInterval(int $idAccount, string $initialDate, string $finalDate): array
    {
        try {
            $sql = "SELECT t.id, c.id as customer_id, ca.id as account_id, ca.number as account_number, t.amount, t.description, t.type, t.created_at, t.updated_at FROM customers as c INNER JOIN customers_accounts as ca ON (c.id = ca.customers_id) INNER JOIN transactions as t ON (t.account_id = ca.id) AND ca.id = :idAccount AND t.created_at >= :initialDate AND t.created_at <= :finalDate";

            $initialDate = $initialDate . " 00:00:00";
            $finalDate = $finalDate . " 23:59:59";

            $params = [
                'idAccount' => $idAccount,
                'initialDate' => $initialDate,
                'finalDate' => $finalDate,
            ];

            $stmt = $this->prepareBind($sql, $params);
            $stmt->execute();

            if (!count($this->fillTransaction($stmt)) > 0) {
                return null;
            }

            $transactions = $this->fillTransaction($stmt);

            return $transactions;
        } catch (Exception $e) {
            throw new Exception("Not possible execute the query");
        }
    }

    /**
     *
     * @param TransactionInterface $account
     * @param int $idAccount
     * @return bool
     */
    public function save(TransactionInterface $transaction): bool
    {
        if (!$transaction->getId()) {
            return $this->insert($transaction, $transaction->getAccountId());
        }

        return $this->update($transaction);
    }

    /**
     *            
     * @param CustomerAccountInterface $account
     * @return bool
     */
    private function insert(TransactionInterface $transaction, int $idAccount): bool
    {
        try {
            $sql = "INSERT INTO transactions (current_balance, description, type, customers_id, number) VALUES (:current_balance, :description, :type, :customers_id, :number)";

            $params = [
                'current_balance' => $transaction->getAmount(),
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
            throw new Exception($e->getMessage());
            throw new Exception("Not possible save the customer");
        }
    }

    /**
     *
     * @param CustomerInterface $customer
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
            throw new Exception($e->getMessage());
            throw new Exception("Not possible update the customer");
        }
    }

    /**
     *
     * @param CustomerInterface $customer
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
            throw new Exception("Not possible delete the customer");
        }
    }
}

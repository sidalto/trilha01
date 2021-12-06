<?php

namespace App\Repositories\CustomerAccountRepository;

use App\Models\Customer\CustomerInterface;
use PDO;
use Exception;
use PDOStatement;
use DateTimeImmutable;
use App\Models\CustomerAccount\CustomerAccount;
use App\Models\CustomerAccount\CustomerAccountInterface;
use App\Repositories\Traits\PrepareDatabaseSql;
use App\Repositories\CustomerAccountRepository\CustomerAccountRepositoryInterface;

class CustomerAccountRepository implements CustomerAccountRepositoryInterface
{
    use PrepareDatabaseSql;

    private CustomerAccountInterface $account;

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
     * Fill the CustomerAccountInterface object
     *
     * @param PDOStatement $stmt
     * @return array
     */
    public function fillAccount(PDOStatement $stmt): array
    {
        try {
            $stmt->execute();
            $accountList = [];

            while ($accountData = $stmt->fetch()) {
                $account = new CustomerAccount();
                $account->fill(
                    $accountData['current_balance'],
                    $accountData['type'],
                    $accountData['description'],
                    $accountData['number'],
                    $accountData['id'],
                    new DateTimeImmutable($accountData['created_at']),
                    $accountData['updated_at'] ? new DateTimeImmutable($accountData['updated_at']) : NULL
                );

                $accountList[] = $account;
            }

            return $accountList;
        } catch (Exception $e) {
            // throw new Exception("Not possible execute the query");
            throw new Exception($e->getMessage());
        }
    }

    /**
     *
     * @return array
     */
    public function findAllByCustomer(int $idCustomer): array
    {
        try {
            $sql = "SELECT c.id, ca.id as ca_id, ca.customers_id, ca.type, ca.description, ca.number, ca.current_balance, ca.created_at, ca.updated_at FROM customers_accounts as ca JOIN customers as c ON (c.id = ca.customers_id) AND c.is_company AND c.id = :id";

            $params = ['id' => $idCustomer];
            $stmt = $this->prepareBind($sql, $params);

            return $this->fillAccount($stmt);
        } catch (Exception $e) {
            // throw new Exception("Not possible execute the query");
            throw new Exception($e->getMessage());
        }
    }

    /**
     *
     * @param int $idAccount
     * @param int $idCustomer
     * @return CustomerInterface
     */
    public function findOneByCustomer(int $idAccount, int $idCustomer = 0): ?CustomerAccountInterface
    {
        try {
            $sql = "SELECT ca.id, ca.customers_id, ca.type, ca.description, ca.number, ca.current_balance, ca.created_at as created_at, ca.updated_at as updated_at FROM customers_accounts as ca WHERE ca.customers_id = :idCustomer AND ca.id = :idAccount";

            $params = [
                'idAccount' => $idAccount,
                'idCustomer' => $idCustomer
            ];

            $stmt = $this->prepareBind($sql, $params);
            $stmt->execute();

            if (!count($this->fillAccount($stmt)) > 0) {
                return null;
            }

            $account = $this->fillAccount($stmt);

            return array_shift($account);
        } catch (Exception $e) {
            throw new Exception("Not possible execute the query");
        }
    }

    /**
     *
     * @param int $idCustomer
     * @param int $idAccount
     * @return CustomerInterface
     */
    public function findByAccountNumber(int $accountNumber): ?CustomerAccountInterface
    {
        try {
            $sql = "SELECT ca.id, ca.customers_id, ca.type, ca.description, ca.number, ca.current_balance, ca.created_at as created_at, ca.updated_at as updated_at FROM customers_accounts as ca WHERE ca.number = :accountNumber";

            $params = ['number' => $accountNumber];

            $stmt = $this->prepareBind($sql, $params);
            $stmt->execute();

            if (!count($this->fillAccount($stmt)) > 0) {
                return null;
            }

            $customer = $this->fillAccount($stmt);

            return array_shift($customer);
        } catch (Exception $e) {
            throw new Exception("Not possible execute the query");
        }
    }

    /**
     *
     * @param CustomerAccountInterface $account
     * @return bool
     */
    public function save(CustomerAccountInterface $account, int $idCustomer): ?int
    {
        if (!$account->getId()) {
            return $this->insert($account, $idCustomer);
        }

        return $this->update($account, $idCustomer);
    }

    /**
     *            
     * @param CustomerAccountInterface $account
     * @return int|null
     */
    private function insert(CustomerAccountInterface $account, string $customerId): ?int
    {
        try {
            $sql = "INSERT INTO customers_accounts (current_balance, description, type, customers_id, number) VALUES (:current_balance, :description, :type, :customers_id, :number)";

            $params = [
                'current_balance' => $account->getCurrentBalance(),
                'description' => $account->getDescription(),
                'type' => $account->getTypeAccount(),
                'customers_id' => $customerId,
                'number' => $account->getNumber()
            ];

            $stmt = $this->prepareBind($sql, $params);
            $result = $stmt->execute();

            if ($result) {
                $account->setId($this->getInsertId());
            }

            return $account->getId();
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
            throw new Exception("Not possible save the customer");
        }
    }

    /**
     *
     * @param CustomerInterface $customer
     * @return int|null
     */
    private function update(CustomerAccountInterface $account, int $idCustomer): ?int
    {
        try {

            $sql = "UPDATE customers_accounts SET current_balance = :current_balance, description = :description, type = :type, customers_id = :customers_id WHERE id = :id";

            $params = [
                'current_balance' => $account->getCurrentBalance(),
                'description' => $account->getDescription(),
                'type' => $account->getTypeAccount(),
                'customers_id' => $idCustomer,
                'id' => $account->getId()
            ];

            $stmt = $this->prepareBind($sql, $params);
            $result = $stmt->execute();

            if ($result) {
                return $account->getId();
            }

            return $result;
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
    public function remove(CustomerAccountInterface $account): bool
    {
        try {
            $sql = "DELETE FROM customers_accounts WHERE id = :id";

            $params = ['id' => $account->getId()];
            $stmt = $this->prepareBind($sql, $params);

            return $stmt->execute();
        } catch (Exception $e) {
            throw new Exception("Not possible delete the customer");
        }
    }
}

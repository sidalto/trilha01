<?php

namespace App\Repositories\CustomerAccountRepository;

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
     * @param PDO $connection
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
        } catch (PDOStatement $e) {
            throw new PDOStatement($e);
        }
    }

    /**
     * @param int $idCustomer
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
            throw new Exception("Não foi possível realizar a busca");
        }
    }

    /**
     * @param int $idAccount
     * @param int $idCustomer
     * @return CustomerAccountInterface|null
     */
    public function findOneByCustomer(int $idAccount, int $idCustomer): ?CustomerAccountInterface
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
            throw new Exception("Não foi possível realizar a busca");
        }
    }

    /**
     * @param int $accountNumber
     * @return CustomerAccountInterface|null
     */
    public function findByAccountNumber(int $accountNumber): ?CustomerAccountInterface
    {
        try {
            $sql = "SELECT ca.id, ca.customers_id, ca.type, ca.description, ca.number, ca.current_balance, ca.created_at as created_at, ca.updated_at as updated_at FROM customers_accounts as ca WHERE ca.number = :accountNumber";

            $params = ['accountNumber' => $accountNumber];

            $stmt = $this->prepareBind($sql, $params);
            $stmt->execute();

            if (!count($this->fillAccount($stmt)) > 0) {
                return null;
            }

            $customer = $this->fillAccount($stmt);
            return array_shift($customer);
        } catch (Exception $e) {
            throw new Exception("Não foi possível realizar a consulta");
        }
    }

    /**
     * @param CustomerAccountInterface $account
     * @param int $idCustomer
     * @return int|null
     */
    public function save(CustomerAccountInterface $account, int $idCustomer): ?int
    {
        try {
            if (!$account->getId()) {
                return $this->insert($account, $idCustomer);
            }

            return $this->update($account);
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * @param CustomerAccountInterface $account
     * @param string $customerId
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
            throw new Exception("Não foi possivel salvar a conta");
        }
    }

    /**
     * @param CustomerAccountInterface $account
     * @return int|null
     */
    private function update(CustomerAccountInterface $account): ?int
    {
        try {
            $sql = "UPDATE customers_accounts SET current_balance = :current_balance, description = :description, type = :type WHERE id = :id";

            $params = [
                'current_balance' => $account->getCurrentBalance(),
                'description' => $account->getDescription(),
                'type' => $account->getTypeAccount(),
                'id' => $account->getId()
            ];

            $stmt = $this->prepareBind($sql, $params);
            $result = $stmt->execute();

            if ($result) {
                return $account->getId();
            }

            return $result;
        } catch (Exception $e) {
            throw new Exception("Não foi possível atualizar a conta");
        }
    }

    /**
     * @param CustomerAccountInterface $account
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
            throw new Exception("Não foi possível excluir a conta");
        }
    }
}

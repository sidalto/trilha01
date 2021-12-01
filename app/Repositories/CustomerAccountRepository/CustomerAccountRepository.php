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
     * Fill the CustomerInterface object
     *
     * @param PDOStatement $stmt
     * @return array
     */
    public function fillCustomer(PDOStatement $stmt): array
    {
        try {
            $stmt->execute();
            $accountList = [];

            while ($accountData = $stmt->fetch()) {
                // if (!array_key_exists($accountData['id'], $accountList)) {
                //     $customer = new CustomerAccount();
                //     $customer->fill(
                //         $accountData['address'],
                //         $accountData['telephone'],
                //         $accountData['email'],
                //         $accountData['password'],
                //         $accountData['company_name'],
                //         $accountData['cnpj'],
                //         $accountData['state_registration'],
                //         $accountData['foundation_date'] ? new DateTimeImmutable($accountData['foundation_date']) : NULL,
                //         $accountData['id'],
                //         $accountData['created_at'] ? new DateTimeImmutable($accountData['created_at']) : NULL,
                //         $accountData['updated_at'] ? new DateTimeImmutable($accountData['updated_at']) : NULL
                //     );
                // }

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

                $accountList[$accountData['id']] = $account;
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
            $sql = "SELECT c.id, ca.id as ca_id, ca.customers_id, ca.type, ca.description, ca.number, ca.current_balance, ca.created_at, ca.updated_at FROM customers_accounts as ca JOIN customers as c ON (c.id = ca.customers_id) AND c.id = :id";

            $params = ['id' => $idCustomer];
            $stmt = $this->prepareBind($sql, $params);

            return $this->fillCustomer($stmt);
        } catch (Exception $e) {
            // throw new Exception("Not possible execute the query");
            throw new Exception($e->getMessage());
        }
    }

    /**
     *
     * @param int $idCustomer
     * @param int $idAccount
     * @return CustomerInterface
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

            if (!count($this->fillCustomer($stmt)) > 0) {
                return null;
            }

            $customer = $this->fillCustomer($stmt);

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
    public function save(CustomerAccountInterface $account, int $idCustomer): bool
    {
        if (!$account->getId()) {
            return $this->insert($account, $idCustomer);
        }

        return $this->update($account);
    }

    /**
     *            
     * @param CustomerAccountInterface $account
     * @return bool
     */
    private function insert(CustomerAccountInterface $account, string $customerId): bool
    {
        try {
            $sql = "INSERT INTO customers_accounts (current_balance, description, type, customers_id, number) VALUES (:current_balance, :description, :type, :customers_id, :number)";

            $params = [
                'current_balance' => $account->getCurrentBalance(),
                'description' => $account->getDescription(),
                'type' => $account->getType(),
                'customers_id' => $customerId,
                'number' => $account->getNumber()
            ];

            $stmt = $this->prepareBind($sql, $params);
            $result = $stmt->execute();

            if ($result) {
                $account->setId($this->getInsertId());
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
    private function update(CustomerInterface $customer): bool
    {
        try {

            $sql = "UPDATE customers SET company_name = :company_name, cnpj = :cnpj, state_registration = :state_registration, foundation_date = :foundation_date,  address = :address, telephone = :telephone, email = :email, password = :password WHERE id = :id";

            $params = [
                'company_name' => $customer->getCompanyName(),
                'cnpj' => $customer->getCnpj(),
                'state_registration' => $customer->getStateRegistration(),
                'foundation_date' => $customer->getFoundationDate()->format('Y-m-d'),
                'address' => $customer->getAddress(),
                'telephone' => $customer->getTelephone(),
                'email' => $customer->getEmail(),
                'password' => password_hash($customer->getPassword(), PASSWORD_DEFAULT),
                'id' => $customer->getId()
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
    public function remove(CustomerAccountInterface $account): bool
    {
        try {
            $sql = "DELETE FROM customers WHERE id = :id";

            $params = ['id' => $account->getId()];
            $stmt = $this->prepareBind($sql, $params);

            return $stmt->execute();
        } catch (Exception $e) {
            throw new Exception("Not possible delete the customer");
        }
    }
}

<?php

namespace App\Repositories\CustomerRepository;

use PDO;
use Exception;
use PDOStatement;
use DateTimeImmutable;
use App\Models\Customer\CustomerInterface;
use App\Models\Customer\CustomerCompany;
use App\Models\CustomerAccount\CustomerAccount;
use App\Models\CustomerAccount\CustomerAccountInterface;
use App\Repositories\Traits\PrepareDatabaseSql;
use App\Repositories\CustomerRepository\CustomerRepositoryInterface;

class CustomerCompanyRepository implements CustomerRepositoryInterface
{
    use PrepareDatabaseSql;

    private CustomerInterface $customer;
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
            $customersList = [];

            while ($customerData = $stmt->fetch()) {
                if (!array_key_exists($customerData['id'], $customersList)) {
                    $customer = new CustomerCompany();
                    $customer->fill(
                        $customerData['address'],
                        $customerData['telephone'],
                        $customerData['email'],
                        $customerData['password'],
                        $customerData['company_name'],
                        $customerData['cnpj'],
                        $customerData['state_registration'],
                        $customerData['foundation_date'] ? new DateTimeImmutable($customerData['foundation_date']) : NULL,
                        $customerData['id'],
                        $customerData['created_at'] ? new DateTimeImmutable($customerData['created_at']) : NULL,
                        $customerData['updated_at'] ? new DateTimeImmutable($customerData['updated_at']) : NULL
                    );
                }

                $account = new CustomerAccount();
                $account->fill(
                    $customerData['ac_id'],
                    $customerData['current_balance'],
                    $customerData['type'],
                    new DateTimeImmutable($customerData['ac_created_at']),
                    $customerData['description'],
                    $customerData['ac_updated_at'] ? new DateTimeImmutable($customerData['updated_at']) : NULL,
                    $customerData['number']
                );

                $customersList[$customer->getId()] = $customer;
                $customersList[$customer->getId()]->addAccount($account);
            }

            return $customersList;
        } catch (Exception $e) {
            // throw new Exception("Not possible execute the query");
            throw new Exception($e->getMessage());
        }
    }

    /**
     *
     * @return array
     */
    public function findAll(): array
    {
        try {
            $sql = "SELECT c.id, c.company_name, c.cnpj, c.state_registration, c.foundation_date, c.address, c.telephone, c.email, c.created_at, c.updated_at, c.password, c.is_company, ca.id as ac_id, ca.type, ca.description, ca.number, ca.current_balance, ca.created_at as ac_created_at, ca.updated_at as ac_updated_at FROM customers as c JOIN customers_accounts as ca ON (c.id = ca.customers_id) AND c.is_company";

            $stmt = $this->prepareBind($sql);

            return $this->fillCustomer($stmt);
        } catch (Exception $e) {
            // throw new Exception("Not possible execute the query");
            throw new Exception($e->getMessage());
        }
    }

    /**
     *
     * @param CustomerInterface $customer
     * @return CustomerInterface
     */
    public function findOne(string $id): ?CustomerInterface
    {
        try {
            $sql = "SELECT c.id, c.company_name, c.cnpj, c.state_registration, c.foundation_date, c.address, c.telephone, c.email, c.created_at, c.updated_at, c.password, c.is_company, ca.id as ac_id, ca.type, ca.description, ca.number, ca.current_balance, ca.created_at as ac_created_at, ca.updated_at as ac_updated_at FROM customers as c JOIN customers_accounts as ca ON (c.id = ca.customers_id) WHERE c.is_company AND ca.customers_id = c.id AND c.id = :id";

            $params = ['id' => $id];
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
     * @param CustomerInterface $customer
     * @return bool
     */
    public function save(CustomerInterface $customer): bool
    {
        if (!$customer->getId()) {
            return $this->insert($customer);
        }

        return $this->update($customer);
    }

    /**
     *            
     * @param CustomerInterface $customer
     * @return bool
     */
    private function insert(CustomerInterface $customer): bool
    {
        try {
            $sql = "INSERT INTO customers (company_name, cnpj, state_registration, foundation_date, address, telephone, email, password, is_company) VALUES (:company_name, :cnpj, :state_registration, :foundation_date, :address, :telephone, :email, :password, :is_company);";

            $params = [
                'company_name' => $customer->getCompanyName(),
                'cnpj' => $customer->getCnpj(),
                'state_registration' => $customer->getStateRegistration(),
                'foundation_date' => $customer->getFoundationDate()->format('Y-m-d'),
                'address' => $customer->getAddress(),
                'telephone' => $customer->getTelephone(),
                'email' => $customer->getEmail(),
                'password' => password_hash($customer->getPassword(), PASSWORD_DEFAULT),
                'is_company' => 1,
            ];

            $stmt = $this->prepareBind($sql, $params);
            $result = $stmt->execute();

            if ($result) {
                $customer->setId($this->getInsertId());
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
    public function remove(CustomerInterface $customer): bool
    {
        try {
            $sql = "DELETE FROM customers WHERE id = :id";

            $params = ['id' => $customer->getId()];
            $stmt = $this->prepareBind($sql, $params);

            return $stmt->execute();
        } catch (Exception $e) {
            throw new Exception("Not possible delete the customer");
        }
    }
}

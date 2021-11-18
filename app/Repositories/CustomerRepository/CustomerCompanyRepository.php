<?php

namespace App\Repositories\CustomerRepository;

use App\Repositories\CustomerRepository\CustomerRepositoryInterface;
use App\Models\Customer\CustomerInterface;
use App\Repositories\Traits\PrepareDatabaseSql;
use DateTimeImmutable;
use Exception;
use PDO;
use PDOStatement;

class CustomerCompanyRepository implements CustomerRepositoryInterface
{
    use PrepareDatabaseSql;

    private CustomerInterface $customer;

    /**
     * 
     * @param PDO $connection
     * @param CustomerInterface $customer
     */
    public function __construct(PDO $connection, CustomerInterface $customer)
    {
        self::$connection = $connection;
        $this->customer = $customer;
    }

    /**
     * Fill the CustomerInterface object
     * 
     * @param PDOStatement $stmt
     */
    public function fillCustomer(PDOStatement $stmt): array
    {
        try {
            $stmt->execute();
            $customersList = [];

            while ($customerData = $stmt->fetch()) {
                $customersList[] = new $this->customer(
                    $customerData['address'],
                    $customerData['telephone'],
                    new DateTimeImmutable($customerData['created_at']),
                    $customerData['email'],
                    $customerData['password'],
                    (int)$customerData['id'],
                    $customerData['company_name'],
                    $customerData['cnpj'],
                    $customerData['state_registration'],
                    $customerData['foundation_date'] ? new DateTimeImmutable($customerData['foundation_date']) : NULL,
                    $customerData['updated_at'] ? new DateTimeImmutable($customerData['updated_at']) : NULL
                );
            }

            return $customersList;
        } catch (Exception $e) {
            throw new Exception("Not possible execute the query");
        }
    }

    /**
     *
     * @return array
     */
    public function getAll(): array
    {
        try {
            $sql = "SELECT * FROM customers WHERE is_company";
            $stmt = $this->prepareBind($sql);
            $stmt->execute();

            return $this->fillCustomer($stmt);
        } catch (Exception $e) {
            throw new Exception("Not possible execute the query");
        }
    }

    /**
     *
     * @return CustomerInterface
     */
    public function getById(CustomerInterface $customer): CustomerInterface
    {
        try {
            $this->customer = $customer;

            $sql = "SELECT * FROM customers WHERE is_company AND id = :id";
            $params = ['id' => $this->customer->getId()];
            $stmt = $this->prepareBind($sql, $params);
            $stmt->execute();
            $this->customer = $this->fillCustomer($stmt)[0];

            return $this->customer;
        } catch (Exception $e) {
            throw new Exception("Not possible execute the query");
        }
    }

    /**
     *
     * @param CustomerInterface $customer
     * @return bool
     */
    public function add(CustomerInterface $customer): CustomerInterface
    {
        try {
            $this->customer = $customer;

            $sql = "INSERT INTO customers (company_name, cnpj, state_registration, foundation_date, address, telephone, email, password, is_company) VALUES (:company_name, :cnpj, :state_registration, :foundation_date, :address, :telephone, :email, :password, :is_company);";

            $params = [
                'company_name' => $this->customer->getCompanyName(),
                'cnpj' => $this->customer->getCnpj(),
                'state_registration' => $this->customer->getStateRegistration(),
                'foundation_date' => $this->customer->getFoundationDate()->format('Y-m-d'),
                'address' => $this->customer->getAddress(),
                'telephone' => $this->customer->getTelephone(),
                'email' => $this->customer->getEmail(),
                'password' => password_hash($this->customer->getPassword(), PASSWORD_DEFAULT),
                'is_company' => 1,
            ];

            $stmt = $this->prepareBind($sql, $params);
            $stmt->execute();
            $this->customer->setId($this->getInsertId());

            return $this->customer;
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    /**
     *
     * @param int $id
     * @return CustomerInterface
     */
    public function edit(CustomerInterface $customer): CustomerInterface
    {
        try {
            $this->customer = $customer;

            $sql = "UPDATE customers SET company_name = :company_name, cnpj = :cnpj, state_registration = :state_registration, foundation_date = :foundation_date,  address = :address, telephone = :telephone, email = :email, password = :password WHERE id = :id;";

            $params = [
                'company_name' => $this->customer->getCompanyName(),
                'cnpj' => $this->customer->getCnpj(),
                'state_registration' => $this->customer->getStateRegistration(),
                'foundation_date' => $this->customer->getFoundationDate() ? $this->customer->getFoundationDate()->format('Y-m-d') : NULL,
                'address' => $this->customer->getAddress(),
                'telephone' => $this->customer->getTelephone(),
                'email' => $this->customer->getEmail(),
                'password' => password_hash($this->customer->getPassword(), PASSWORD_DEFAULT),
                'id' => $this->customer->getId()
            ];

            $stmt = $this->prepareBind($sql, $params);
            $stmt->execute();

            return $this->customer;
        } catch (Exception $e) {
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
            $this->customer = $customer;

            $sql = "DELETE FROM customers WHERE id = :id;";

            $params = ['id' => $this->customer->getId()];
            $stmt = $this->prepareBind($sql, $params);

            return $stmt->execute();
        } catch (Exception $e) {
            throw new Exception("Not possible delete the customer");
        }
    }
}

<?php

namespace App\Repositories\CustomerRepository;

use PDO;
use Exception;
use PDOStatement;
use DateTimeImmutable;
use App\Models\Customer\CustomerInterface;
use App\Models\CustomerAccount\CustomerAccountInterface;
use App\Repositories\Traits\PrepareDatabaseSql;
use App\Repositories\CustomerRepository\CustomerRepositoryInterface;

class CustomerPersonRepository implements CustomerRepositoryInterface
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
                    $customersList[$customerData['id']] = new $this->customer(
                        $customerData['address'],
                        $customerData['telephone'],
                        new DateTimeImmutable($customerData['created_at']),
                        $customerData['email'],
                        $customerData['password'],
                        (int)$customerData['id'],
                        $customerData['person_name'],
                        $customerData['cpf'],
                        $customerData['rg'],
                        $customerData['birth_date'] ? new DateTimeImmutable($customerData['birth_date']) : NULL,
                        $customerData['updated_at'] ? new DateTimeImmutable($customerData['updated_at']) : NULL
                    );
                }

                $customerAccount = new CustomerAccountInterface(
                    $customerData['number'],
                    $customerData['current_balance'],
                    $customerData['type_account'],
                    new DateTimeImmutable($customerData['created_at']),
                    $customerData['description'],
                );

                $customersList[$customerData['id']]->addAccount($customerAccount);
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
    public function findAll(): array
    {
        try {
            $sql = "SELECT 
                        customers.id,
                        customers.person_name,
                        customers.cpf,
                        customers.rg,
                        customers.birth_date,
                        customers.address,
                        customers.telephone,
                        customers.email,
                        accounts.number,
                        accounts.current_balance,
                        accounts.type_account,
                        accounts.description,
                        accounts.created_at
                    FROM customers, accounts
                    WHERE 
                        NOT is_company
                        AND accounts.customer_id = customers.id
                        AND customers.id = :id";

            $params = ['id' => $this->customer->getId()];
            $stmt = $this->prepareBind($sql, $params);

            return $this->fillCustomer($stmt);
        } catch (Exception $e) {
            throw new Exception("Not possible execute the query");
        }
    }

    /**
     *
     * @param CustomerInterface $customer
     * @return CustomerInterface
     */
    public function findOne(string $number): CustomerInterface
    {
        try {
            $sql = "SELECT * FROM customers WHERE NOT is_company AND number = :number";
            $params = ['number' => $number];
            $stmt = $this->prepareBind($sql, $params);
            $stmt->execute();

            return array_shift($this->fillCustomer($stmt));
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
     * @var CustomerInterface $customer
     * @return bool
     */
    public function insert(CustomerInterface $customer): bool
    {
        try {
            $sql = "INSERT INTO customers (person_name, cpf, rg, birth_date, address, telephone, email, password, is_company) VALUES (:person_name, :cpf, :rg, :birth_date, :address, :telephone, :email, :password, :is_company);";

            $params = [
                'person_name' => $customer->getPersonName(),
                'cpf' => $customer->getCpf(),
                'rg' => $customer->getRg(),
                'birth_date' => $customer->getBirthDate()->format('Y-m-d'),
                'address' => $customer->getAddress(),
                'telephone' => $customer->getTelephone(),
                'email' => $customer->getEmail(),
                'password' => password_hash($customer->getPassword(), PASSWORD_DEFAULT),
                'is_company' => 0,
            ];

            $stmt = $this->prepareBind($sql, $params);
            $result = $stmt->execute();

            if ($result) {
                $customer->setId($this->getInsertId());
            }

            return $result;
        } catch (Exception $e) {
            throw new Exception("Not possible add the customer");
        }
    }

    /**
     *
     * @var CustomerInterface $customer
     * @return bool
     */
    public function update(CustomerInterface $customer): bool
    {
        try {
            $sql = "UPDATE customers SET person_name = :person_name, cpf = :cpf, rg = :rg, birth_date = :birth_date, address = :address, telephone = :telephone, email = :email, password = :password WHERE id = :id;";

            $params = [
                'person_name' => $customer->getPersonName(),
                'cpf' => $customer->getCpf(),
                'rg' => $customer->getRg(),
                'birth_date' => $customer->getBirthDate() ? $customer->getBirthDate()->format('Y-m-d') : NULL,
                'address' => $customer->getAddress(),
                'telephone' => $customer->getTelephone(),
                'email' => $customer->getEmail(),
                'password' => password_hash($customer->getPassword(), PASSWORD_DEFAULT),
                'id' => $customer->getId()
            ];

            $stmt = $this->prepareBind($sql, $params);

            return $stmt->execute();
        } catch (Exception $e) {
            throw new Exception("Not possible update the customer");
        }
    }

    /**
     *
     * @var CustomerInterface $customer
     * @return bool
     */
    public function remove(CustomerInterface $customer): bool
    {
        try {
            $sql = "DELETE FROM customers WHERE id = :id;";

            $params = ['id' => $customer->getId()];
            $stmt = $this->prepareBind($sql, $params);

            return $stmt->execute();
        } catch (Exception $e) {
            throw new Exception("Not possible delete the customer");
        }
    }
}

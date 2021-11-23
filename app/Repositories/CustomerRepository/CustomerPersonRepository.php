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
     * Fill the Customer object
     * 
     * @param PDOStatement $stmt
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

    public function getAll(): array
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
     * @return CustomerInterface
     */
    public function getById(CustomerInterface $customer): CustomerInterface
    {
        try {
            $this->customer = $customer;

            $sql = "SELECT * FROM customers WHERE NOT is_company AND id = :id";
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
     * @var CustomerInterface $customer
     * @return bool
     */
    public function add(CustomerInterface $customer): CustomerInterface
    {
        try {
            $this->customer = $customer;

            $sql = "INSERT INTO customers (person_name, cpf, rg, birth_date, address, telephone, email, password, is_company) VALUES (:person_name, :cpf, :rg, :birth_date, :address, :telephone, :email, :password, :is_company);";

            $params = [
                'person_name' => $this->customer->getPersonName(),
                'cpf' => $this->customer->getCpf(),
                'rg' => $this->customer->getRg(),
                'birth_date' => $this->customer->getBirthDate()->format('Y-m-d'),
                'address' => $this->customer->getAddress(),
                'telephone' => $this->customer->getTelephone(),
                'email' => $this->customer->getEmail(),
                'password' => password_hash($this->customer->getPassword(), PASSWORD_DEFAULT),
                'is_company' => 0,
            ];

            $stmt = $this->prepareBind($sql, $params);
            $stmt->execute();
            $this->customer->setId($this->getInsertId());

            return $this->customer;
        } catch (Exception $e) {
            throw new Exception("Not possible add the customer");
        }
    }

    /**
     *
     * @var CustomerInterface $customer
     * @return CustomerInterface
     */
    public function edit(CustomerInterface $customer): CustomerInterface
    {
        try {
            $this->customer = $customer;

            $sql = "UPDATE customers SET person_name = :person_name, cpf = :cpf, rg = :rg, birth_date = :birth_date, address = :address, telephone = :telephone, email = :email, password = :password WHERE id = :id;";

            $params = [
                'person_name' => $this->customer->getPersonName(),
                'cpf' => $this->customer->getCpf(),
                'rg' => $this->customer->getRg(),
                'birth_date' => $this->customer->getBirthDate() ? $this->customer->getBirthDate()->format('Y-m-d') : NULL,
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
            // throw new Exception("Not possible update the customer");
            throw new Exception($e->getMessage());
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

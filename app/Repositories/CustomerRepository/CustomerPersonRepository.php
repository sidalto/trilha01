<?php

namespace App\Repositories\CustomerRepository;

use PDO;
use Exception;
use PDOStatement;
use DateTimeImmutable;
use App\Models\Customer\CustomerInterface;
use App\Models\Customer\CustomerPerson;
use App\Models\CustomerAccount\CustomerAccount;
use App\Repositories\Traits\PrepareDatabaseSql;
use App\Models\CustomerAccount\CustomerAccountInterface;
use App\Repositories\CustomerRepository\CustomerRepositoryInterface;

class CustomerPersonRepository implements CustomerRepositoryInterface
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
                    $customer = new CustomerPerson();
                    $customer->fill(
                        $customerData['address'],
                        $customerData['telephone'],
                        $customerData['email'],
                        $customerData['password'],
                        $customerData['person_name'],
                        $customerData['cpf'],
                        $customerData['rg'],
                        $customerData['birth_date'] ? new DateTimeImmutable($customerData['birth_date']) : NULL,
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
            $sql = "SELECT c.id, c.person_name, c.cpf, c.rg, c.birth_date, c.address, c.telephone, c.email, c.created_at, c.updated_at, c.password, c.is_company, ca.id as ac_id, ca.type, ca.description, ca.number, ca.current_balance, ca.created_at as ac_created_at, ca.updated_at as ac_updated_at FROM customers as c JOIN customers_accounts as ca ON (c.id = ca.customers_id) AND NOT c.is_company";

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
    public function findOne(string $id): CustomerInterface
    {
        try {
            $sql = "SELECT c.id, c.person_name, c.cpf, c.rg, c.birth_date, c.address, c.telephone, c.email, c.created_at, c.updated_at, c.password, c.is_company, ca.id as ac_id, ca.type, ca.description, ca.number, ca.current_balance, ca.created_at as ac_created_at, ca.updated_at as ac_updated_at FROM customers as c JOIN customers_accounts as ca ON (c.id = ca.customers_id) WHERE NOT c.is_company AND ca.customers_id = c.id AND c.id = :id";

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

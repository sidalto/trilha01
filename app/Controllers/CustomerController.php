<?php

namespace App\Controllers;

use App\Http\Request;
use DateTimeImmutable;
use App\Database\Connection;
use App\Models\Customer\CustomerPerson;
use App\Models\CustomerAccount\CustomerAccount;
use App\Repositories\CustomerRepository\CustomerPersonRepository;
use App\Repositories\CustomerRepository\CustomerRepositoryInterface;

class CustomerController
{
    private CustomerRepositoryInterface $customerRepository;

    public function __construct()
    {
        $this->customerRepository = new CustomerPersonRepository(Connection::getInstance());
        $this->request = new Request();
    }

    public function index()
    {
        var_dump($this->customerRepository->findAll());
    }

    public function getById(array $data)
    {
        $id = $data['params'];
        var_dump($this->customerRepository->findOne($id));
    }

    public function create(array $data)
    {
        $customer = new CustomerPerson();

        $customer->fill(
            $data['address'],
            $data['telephone'],
            $data['email'],
            $data['password'],
            $data['person_name'],
            $data['cpf'],
            $data['rg'],
            new DateTimeImmutable($data['birth_date'])
        );

        $this->customerRepository->save($customer);
    }

    public function update(array $data)
    {
        $id = $data['id'];
        $data = $data['data'];

        $existentCustomer = $this->customerRepository->findOne($id);

        if (!$existentCustomer) {
            var_dump("Not possible update");
            exit;
        }

        $customer = new CustomerPerson();
        $customer->fill(
            isset($data['telephone']) ? $data['telephone'] : $existentCustomer->getTelephone(),
            isset($data['address']) ? $data['address'] : $existentCustomer->getAddress(),
            isset($data['email']) ? $data['email'] : $existentCustomer->getEmail(),
            isset($data['password']) ? $data['password'] : $existentCustomer->getPassword(),
            isset($data['person_name']) ? $data['person_name'] : $existentCustomer->getPersonName(),
            isset($data['cpf']) ? $data['cpf'] : $existentCustomer->getCpf(),
            isset($data['rg']) ? $data['rg'] : $existentCustomer->getRg(),
            isset($data['birth_date']) ? new DateTimeImmutable($data['birth_date']) : $existentCustomer->getBirthDate(),
            $existentCustomer->getId()
        );

        var_dump($this->customerRepository->save($customer));
    }

    public function delete(array $data)
    {
        $id = $data['params'];
        $existentCustomer = $this->customerRepository->findOne($id);

        if (!$existentCustomer) {
            var_dump("Not possible delete");
            exit;
        }

        var_dump($this->customerRepository->remove($existentCustomer));
    }
}

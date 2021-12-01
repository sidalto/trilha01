<?php

namespace App\Controllers;

use DateTimeImmutable;
use App\Database\Connection;
use App\Models\Customer\CustomerPerson;
use App\Repositories\CustomerRepository\CustomerPersonRepository;
use App\Repositories\CustomerRepository\CustomerRepositoryInterface;

use function App\Helpers\input;

class CustomerController
{
    private CustomerRepositoryInterface $customerRepository;

    public function __construct()
    {
        $this->customerRepository = new CustomerPersonRepository(Connection::getInstance());
    }

    public function index()
    {
        var_dump($this->customerRepository->findAll());
    }

    public function getById(int $id)
    {
        var_dump($this->customerRepository->findOne($id));
    }

    public function create()
    {
        $customer = new CustomerPerson();

        $customer->fill(
            input('address'),
            input('telephone'),
            input('email'),
            input('password'),
            input('person_name'),
            input('cpf'),
            input('rg'),
            new DateTimeImmutable(input('birth_date'))
        );

        $this->customerRepository->save($customer);
    }

    public function update(int $id)
    {
        $existentCustomer = $this->customerRepository->findOne($id);

        if (!$existentCustomer) {
            var_dump("Not possible update");
            exit;
        }

        $customer = new CustomerPerson();
        $customer->fill(
            !empty(input('telephone')) ? input('telephone') : $existentCustomer->getTelephone(),
            !empty(input('address')) ? input('address') : $existentCustomer->getAddress(),
            !empty(input('email')) ? input('email') : $existentCustomer->getEmail(),
            !empty(input('password')) ? input('password') : $existentCustomer->getPassword(),
            !empty(input('person_name')) ? input('person_name') : $existentCustomer->getPersonName(),
            !empty(input('cpf')) ? input('cpf') : $existentCustomer->getCpf(),
            !empty(input('rg')) ? input('rg') : $existentCustomer->getRg(),
            !empty(input('birth_date')) ? new DateTimeImmutable(input('birth_date')) : $existentCustomer->getBirthDate(),
            $existentCustomer->getId()
        );

        var_dump($this->customerRepository->save($customer));
    }

    public function delete(int $id)
    {
        $existentCustomer = $this->customerRepository->findOne($id);

        if (!$existentCustomer) {
            var_dump("Not possible delete");
            exit;
        }

        var_dump($this->customerRepository->remove($existentCustomer));
    }
}

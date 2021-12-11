<?php

namespace App\Controllers;

use DateTimeImmutable;
use App\Database\Connection;
use App\Models\Customer\CustomerPerson;
use App\Models\CustomerAccount\CustomerAccount;
use App\Repositories\CustomerRepository\CustomerPersonRepository;
use App\Repositories\CustomerRepository\CustomerRepositoryInterface;
use App\Repositories\CustomerAccountRepository\CustomerAccountRepository;
use function App\Helpers\input;
use function App\Helpers\response;

class CustomerController
{
    private CustomerRepositoryInterface $customerRepository;

    public function __construct()
    {
        $this->customerRepository = new CustomerPersonRepository(Connection::getInstance());
        $this->accountRepository = new CustomerAccountRepository(Connection::getInstance());
    }

    public function index()
    {
        $result = $this->customerRepository->findAll();

        if (!$result) {
            return response()
                ->httpCode(400)
                ->json([
                    'message' => 'Error',
                    'data' => []
                ]);
        }

        $customers = [];

        foreach ($result as $key => $customer) {
            $customers = [
                'id' => $customer->getId(),
                'person_name' => $customer->getPersonName(),
                'cpf' => $customer->getCpf(),
                'rg' => $customer->getRg(),
                'address' => $customer->getAddress(),
                'telephone' => $customer->getTelephone(),
                'email' => $customer->getEmail(),
                'birth_date' => $customer->getBirthDate()->format('Y-m-d')
            ];

            $accounts = [];
            foreach ($customer->getAccounts() as $account) {
                $accounts[] = [
                    'current_balance' => $account->getCurrentBalance(),
                    'type' => $account->getTypeAccount(),
                    'description' => $account->getDescription(),
                    'number' => $account->getNumber()
                ];
            }

            $customers['accounts'] = $accounts;
        }

        return response()
            ->httpCode(200)
            ->json([
                'message' => 'Success',
                'data' => [$customers]
            ]);
    }

    public function getById(int $id)
    {
        $result = $this->customerRepository->findOne($id);

        if (!$result) {
            return response()
                ->httpCode(400)
                ->json([
                    'message' => 'Error',
                    'data' => []
                ]);
        }

        $accounts = [];

        foreach ($result->getAccounts() as $key => $account) {
            $accounts[$key] = [
                'current_balance' => $account->getCurrentBalance(),
                'type' => $account->getTypeAccount(),
                'description' => $account->getDescription(),
                'number' => $account->getNumber()
            ];
        }

        return response()
            ->httpCode(200)
            ->json([
                'message' => 'Success',
                'data' => [
                    'id' => $result->getId(),
                    'person_name' => $result->getPersonName(),
                    'cpf' => $result->getCpf(),
                    'rg' => $result->getRg(),
                    'address' => $result->getAddress(),
                    'telephone' => $result->getTelephone(),
                    'email' => $result->getEmail(),
                    'birth_date' => $result->getBirthDate()->format('Y-m-d'),
                    'accounts' => $accounts
                ]
            ]);
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

        $account = new CustomerAccount();

        $account->fill(
            0.00,
            0,
            '',
            $account->getNumber()
        );


        $result = $this->customerRepository->save($customer);

        if (!$result) {
            return response()
                ->httpCode(400)
                ->json([
                    'message' => 'Error',
                    'data' => []
                ]);
        }

        $result = $this->accountRepository->save($account, $result);

        return response()
            ->httpCode(200)
            ->json([
                'message' => 'Success',
                'data' => []
            ]);
    }

    public function update(int $id)
    {
        $result = $this->customerRepository->findOne($id);

        if (!$result) {
            return response()
                ->httpCode(400)
                ->json([
                    'message' => 'Error',
                    'data' => []
                ]);
        }

        $customer = new CustomerPerson();
        $customer->fill(
            !empty(input('address')) ? input('address') : $result->getAddress(),
            !empty(input('telephone')) ? input('telephone') : $result->getTelephone(),
            !empty(input('email')) ? input('email') : $result->getEmail(),
            !empty(input('password')) ? input('password') : $result->getPassword(),
            !empty(input('person_name')) ? input('person_name') : $result->getPersonName(),
            !empty(input('cpf')) ? input('cpf') : $result->getCpf(),
            !empty(input('rg')) ? input('rg') : $result->getRg(),
            !empty(input('birth_date')) ? new DateTimeImmutable(input('birth_date')) : $result->getBirthDate(),
            $result->getId()
        );

        $result = $this->customerRepository->save($customer);

        if (!$result) {
            return response()
                ->httpCode(400)
                ->json([
                    'message' => 'Error',
                    'data' => []
                ]);
        }

        return response()
            ->httpCode(200)
            ->json([
                'message' => 'Success',
                'data' => []
            ]);
    }

    public function delete(int $id)
    {
        $result = $this->customerRepository->findOne($id);

        if (!$result) {
            return response()
                ->httpCode(400)
                ->json([
                    'message' => 'Error',
                    'data' => []
                ]);
        }

        $result = $this->customerRepository->remove($result);

        if (!$result) {
            return response()
                ->httpCode(400)
                ->json([
                    'message' => 'Error',
                    'data' => []
                ]);
        }

        return response()
            ->httpCode(200)
            ->json([
                'message' => 'Success',
                'data' => []
            ]);
    }
}

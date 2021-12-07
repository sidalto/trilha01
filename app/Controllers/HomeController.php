<?php

namespace App\Controllers;

use App\Database\Connection;
use App\Repositories\CustomerRepository\CustomerPersonRepository;
use App\Repositories\CustomerAccountRepository\CustomerAccountRepository;
use function App\Helpers\input;
use function App\Helpers\request;
use function App\Helpers\response;

class HomeController
{
    public function __construct()
    {
        $this->customerRepository = new CustomerPersonRepository(Connection::getInstance());
        $this->companyRepository = new CustomerPersonRepository(Connection::getInstance());
        $this->accountRepository = new CustomerAccountRepository(Connection::getInstance());
    }

    public function index()
    {
        $idCustomer = request()->data['id'];
        $name = request()->data['name'];
        $email = request()->data['email'];

        $this->customer = $this->customerRepository->findByEmail($email);
        $this->company = $this->companyRepository->findByEmail($email);

        $customer = $this->customer ?: $this->company;
        $idAccount = $customer->getAccounts()[0]->getId();

        $result = $this->accountRepository->findOneByCustomer($idAccount, $idCustomer);

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
                'data' => [
                    'id' => $result->getId(),
                    'current_balance' => $result->getCurrentBalance(),
                    'number' => $result->getNumber(),
                    'type' => $result->getTypeAccount(),
                    'name' => $name,
                ]
            ]);
    }
}

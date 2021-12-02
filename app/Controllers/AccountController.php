<?php

namespace App\Controllers;

use App\Database\Connection;
use App\Models\CustomerAccount\CustomerAccount;
use App\Repositories\CustomerAccountRepository\CustomerAccountRepository;
use App\Repositories\CustomerAccountRepository\CustomerAccountRepositoryInterface;

use function App\Helpers\input;

class AccountController
{
    private CustomerAccountRepositoryInterface $accountRepository;

    public function __construct()
    {
        $this->accountRepository = new CustomerAccountRepository(Connection::getInstance());
    }

    public function index(int $idCustomer)
    {
        var_dump($this->accountRepository->findAllByCustomer($idCustomer));
    }

    public function getById(int $idAccount, int $idCustomer)
    {
        var_dump($this->accountRepository->findOneByCustomer($idAccount, $idCustomer));
    }

    public function create()
    {
        $account = new CustomerAccount();

        $account->fill(
            input('current_balance'),
            input('type'),
            input('description'),
            $account->getNumber()
        );

        $this->accountRepository->save($account, input('idCustomer'));
    }

    public function update(int $idAccount)
    {
        $existentAccount = $this->accountRepository->findOneByCustomer($idAccount, input('idCustomer'));

        if (!$existentAccount) {
            var_dump("Not possible update");
            exit;
        }

        $account = new CustomerAccount();
        $account->fill(
            !empty(input('current_balance')) ? input('current_balance') : $existentAccount->getCurrentBalance(),
            !empty(input('type')) ? input('type') : $existentAccount->getType(),
            !empty(input('description')) ? input('description') : $existentAccount->getDescription(),
            $account->getNumber()
        );

        var_dump($this->accountRepository->save($account, input('idCustomer')));
    }

    public function delete(int $id)
    {
        $existentAccount = $this->accountRepository->findOneByCustomer($id, input('idCustomer'));

        if (!$existentAccount) {
            var_dump("Not possible delete");
            exit;
        }

        var_dump($this->accountRepository->remove($existentAccount));
    }
}

<?php

namespace App\Controllers;

use App\Database\Connection;
use function App\Helpers\input;
use App\Models\Transaction\Transaction;
use App\Models\CustomerAccount\CustomerAccount;
use App\Repositories\TransactionRepository\TransactionRepository;

use App\Repositories\CustomerAccountRepository\CustomerAccountRepository;
use App\Repositories\TransactionRepository\TransactionRepositoryInterface;

class TransactionController
{
    private TransactionRepositoryInterface $transactionRepository;

    public function __construct()
    {
        $this->transactionRepository = new TransactionRepository(Connection::getInstance());
        $this->accountRepository = new CustomerAccountRepository(Connection::getInstance());
    }

    public function index(int $idAccount)
    {
        var_dump($this->transactionRepository->findAllByAccount($idAccount));
    }

    public function getReport(int $idAccount, string $initialDate, string $finalDate)
    {
        var_dump($this->transactionRepository->findAllByDateInterval($idAccount, $initialDate, $finalDate));
    }

    public function create()
    {
        $transaction = new Transaction();

        $transaction->fill(
            input('current_balance'),
            input('type'),
            input('description'),
            $transaction->getNumber()
        );

        $this->transactionRepository->save($transaction, input('idCustomer'));
    }

    public function withdraw(int $idAccount)
    {
        $existentAccount = $this->transactionRepository->findAllByAccount($idAccount);

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

        var_dump($this->transactionRepository->save($account, input('idCustomer')));
    }

    public function delete(int $id)
    {
        $existentAccount = $this->transactionRepository->findOneByCustomer($id, input('idCustomer'));

        if (!$existentAccount) {
            var_dump("Not possible delete");
            exit;
        }

        var_dump($this->transactionRepository->remove($existentAccount));
    }
}

<?php

namespace App\Controllers;

use App\Database\Connection;
use function App\Helpers\input;
use App\Models\Transaction\Transaction;
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
        $existentAccount = $this->accountRepository->findOneByCustomer(input('idAccount'), input('idCustomer'));

        if (!$existentAccount) {
            var_dump("Not possible update");
            exit;
        }

        var_dump($this->transactionRepository->findAllByAccount($idAccount));
    }

    public function getReport(int $idAccount, string $initialDate, string $finalDate)
    {
        var_dump($this->transactionRepository->findAllByDateInterval($idAccount, $initialDate, $finalDate));
    }

    public function withdraw()
    {
        $existentAccount = $this->accountRepository->findOneByCustomer(input('idAccount'), input('idCustomer'));

        if (!$existentAccount) {
            var_dump("Not possible update");
            exit;
        }

        $transaction = new Transaction();

        $transaction->fill(
            input('account_id'),
            input('type'),
            input('amount'),
            input('description'),
        );


        if (!$existentAccount) {
            var_dump("Not possible update");
            exit;
        }

        var_dump($this->transactionRepository->save($transaction));
    }
}

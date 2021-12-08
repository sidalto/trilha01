<?php

namespace App\Controllers;

use App\Database\Connection;
use App\Models\Transaction\Transaction;
use App\Repositories\TransactionRepository\TransactionRepository;
use App\Repositories\CustomerAccountRepository\CustomerAccountRepository;
use App\Repositories\TransactionRepository\TransactionRepositoryInterface;
use function App\Helpers\input;
use function App\Helpers\request;
use function App\Helpers\response;

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
        $idCustomer = request()->data['id'];
        $result = $this->accountRepository->findOneByCustomer(input('idAccount'), $idCustomer);

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
                'data' => $result
            ]);
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

    public function transfer(int $idAccount)
    {
        $destinationAccountNumber = input('account_number');
        $amount = input('amount');
        $idCustomer = request()->data['id'];

        $transaction = new Transaction();
        $result = $transaction->transfer($idCustomer, $idAccount, $destinationAccountNumber, $amount);

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

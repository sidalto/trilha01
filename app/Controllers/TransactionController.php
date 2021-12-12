<?php

namespace App\Controllers;

use Exception;
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
        try {
            $idCustomer = request()->data['id'];
            $result = $this->accountRepository->findOneByCustomer(input('idAccount'), $idCustomer);

            return response()
                ->httpCode(200)
                ->json([
                    'message' => 'Success',
                    'data' => $result
                ]);
        } catch (Exception $e) {
            return response()
                ->httpCode(400)
                ->json([
                    'message' => $e->getMessage(),
                    'data' => []
                ]);
        }
    }

    public function withdraw(int $idAccount)
    {
        try {
            $amount = input('amount');
            $idCustomer = request()->data['id'];
            $transaction = new Transaction();
            $transaction->withdraw($idCustomer, $idAccount, $amount);

            return response()
                ->httpCode(200)
                ->json([
                    'message' => 'Success',
                    'data' => []
                ]);
        } catch (Exception $e) {
            return response()
                ->httpCode(400)
                ->json([
                    'message' => $e->getMessage(),
                ]);
        }
    }

    public function transfer(int $idAccount)
    {
        try {
            $destinationAccountNumber = input('account_number');
            if (!ctype_digit($destinationAccountNumber)) {
                throw new Exception("Número de conta válido");
            }

            $amount = input('amount');
            $idCustomer = request()->data['id'];
            $transaction = new Transaction();
            $transaction->transfer($idCustomer, $idAccount, $destinationAccountNumber, $amount);

            return response()
                ->httpCode(200)
                ->json([
                    'message' => 'Success',
                    'data' => []
                ]);
        } catch (Exception $e) {
            return response()
                ->httpCode(400)
                ->json([
                    'message' => $e->getMessage(),
                    'data' => []
                ]);
        }
    }

    public function deposit(int $idAccount)
    {
        try {
            $amount = input('amount');
            $idCustomer = request()->data['id'];
            $transaction = new Transaction();
            $transaction->deposit($idCustomer, $idAccount, $amount);

            return response()
                ->httpCode(200)
                ->json([
                    'message' => 'Success',
                    'data' => []
                ]);
        } catch (Exception $e) {
            return response()
                ->httpCode(400)
                ->json([
                    'message' => $e->getMessage(),
                ]);
        }
    }

    public function payment(int $idAccount)
    {
        try {
            $amount = input('amount');
            $description = input('amount');
            $idCustomer = request()->data['id'];
            $transaction = new Transaction();
            $transaction->payment($idCustomer, $idAccount, $amount, $description);

            return response()
                ->httpCode(200)
                ->json([
                    'message' => 'Success',
                    'data' => []
                ]);
        } catch (Exception $e) {
            return response()
                ->httpCode(400)
                ->json([
                    'message' => $e->getMessage(),
                ]);
        }
    }
}

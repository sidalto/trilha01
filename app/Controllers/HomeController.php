<?php

namespace App\Controllers;

use Exception;
use DateInterval;
use DateTimeImmutable;
use App\Database\Connection;
use App\Models\Transaction\Transaction;
use App\Repositories\CustomerRepository\CustomerPersonRepository;
use App\Repositories\CustomerRepository\CustomerCompanyRepository;
use App\Repositories\CustomerAccountRepository\CustomerAccountRepository;
use function App\Helpers\request;
use function App\Helpers\response;

class HomeController
{
    public function __construct()
    {
        $this->customerRepository = new CustomerPersonRepository(Connection::getInstance());
        $this->companyRepository = new CustomerCompanyRepository(Connection::getInstance());
        $this->accountRepository = new CustomerAccountRepository(Connection::getInstance());
        $this->transaction = new Transaction();
    }

    public function index()
    {
        try {
            $idCustomer = request()->data['id'];
            $email = request()->data['email'];
            $result = $this->getCurrentBalance($idCustomer, $email);
            $reportTransactions = $this->getReport($email);
            $transactions = [];

            if ($reportTransactions) {
                foreach ($reportTransactions as $transaction) {
                    $transactions[] = [
                        'transaction_id' => $transaction->getId(),
                        'account_id' => $transaction->getAccountId(),
                        'amount' => $transaction->getAmount(),
                        'type_transaction' => $transaction->getType(),
                        'description' => $transaction->getDescription() ?: '',
                        'created_at' => $transaction->getCreatedAt()->format('d-m-Y H:i:s')
                    ];
                }
            }

            return response()
                ->httpCode(200)
                ->json([
                    'message' => 'Success',
                    'data' => [
                        'id' => $result->getId(),
                        'current_balance' => $result->getCurrentBalance(),
                        'number' => $result->getNumber(),
                        'type_account' => $result->getTypeAccount(),
                        'name' => request()->data['name'],
                        'transactions' => $transactions,
                        'id_client' => $idCustomer
                    ]
                ]);
        } catch (Exception $e) {
            return response()
                ->httpCode(400)
                ->json([
                    'message' => $e->getMessage(),
                ]);
        }
    }

    /**
     * @param int $idCustomer
     * @param string $email
     */
    public function getCurrentBalance(int $idCustomer, string $email)
    {
        try {
            $customer = $this->verifyEmail($email);
            $idAccount = $customer->getAccounts()[0]->getId();
            $result = $this->accountRepository->findOneByCustomer($idAccount, $idCustomer);

            return $result;
        } catch (Exception $e) {
            return response()
                ->httpCode(400)
                ->json([
                    'message' => $e->getMessage(),
                ]);
        }
    }

    /**
     * @param string $email
     */
    public function getReport(string $email)
    {
        try {
            $customer = $this->verifyEmail($email);
            $idAccount = $customer->getAccounts()[0]->getId();
            $initialDate = new DateTimeImmutable('now');
            $finalDate = $initialDate->format('Y-m-d');
            $initialDate = $initialDate->sub(new DateInterval('P10D'))->format('Y-m-d');
            $transactions = $this->transaction->getReportByPeriod($idAccount, $initialDate, $finalDate);

            return $transactions;
        } catch (Exception $e) {
            return response()
                ->httpCode(400)
                ->json([
                    'message' => $e->getMessage(),
                ]);
        }
    }

    /**
     * @param string $email
     */
    public function verifyEmail(string $email)
    {
        try {
            $customer = $this->customerRepository->findByEmail($email);
            $company = $this->companyRepository->findByEmail($email);
            $customer = $customer ?: $company;

            return $customer;
        } catch (Exception $e) {
            return response()
                ->httpCode(400)
                ->json([
                    'message' => $e->getMessage(),
                ]);
        }
    }
}

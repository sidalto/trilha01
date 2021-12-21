<?php

namespace App\Controllers;

use App\Database\Connection;
use App\Models\CustomerAccount\CustomerAccount;
use App\Repositories\CustomerAccountRepository\CustomerAccountRepository;
use App\Repositories\CustomerAccountRepository\CustomerAccountRepositoryInterface;
use function App\Helpers\input;
use function App\Helpers\response;

class AccountController
{
    private CustomerAccountRepositoryInterface $accountRepository;

    public function __construct()
    {
        $this->accountRepository = new CustomerAccountRepository(Connection::getInstance());
    }

    /**
     * @param int $idCustomer
     */
    public function index(int $idCustomer): void
    {
        $result = $this->accountRepository->findAllByCustomer($idCustomer);

        if (!$result) {
            return response()
                ->httpCode(204)
                ->json([
                    'message' => 'Conta não encontrada.',
                    'data' => []
                ]);
        }

        $accounts = [];

        foreach ($result as $key => $account) {
            $accounts[] = [
                'current_balance' => $account->getCurrentBalance(),
                'type' => $account->getTypeAccount(),
                'description' => $account->getDescription(),
                'number' => $account->getNumber(),
                'ca_id' => $idCustomer
            ];
        }

        return response()
            ->httpCode(200)
            ->json([
                'message' => 'Success',
                'data' => $accounts
            ]);
    }

    /**
     * @param int $idAccount
     * @param int $idCustomer
     */
    public function getById(int $idAccount, int $idCustomer): void
    {
        $result = $this->accountRepository->findOneByCustomer($idAccount, $idCustomer);

        if (!$result) {
            return response()
                ->httpCode(204)
                ->json([
                    'message' => 'Conta não encontrada.',
                    'data' => []
                ]);
        }

        $accounts = [];

        return response()
            ->httpCode(200)
            ->json([
                'message' => 'Success',
                'data' => [
                    'id' => $result->getId(),
                    'current_balance' => $result->getCurrentBalance(),
                    'type' => $result->getTypeAccount(),
                    'description' => $result->getDescription(),
                    'number' => $result->getNumber(),
                    'company_id' => $idCustomer
                ]
            ]);
    }

    public function create(): void
    {
        $account = new CustomerAccount();
        $account->fill(
            input('current_balance'),
            input('type'),
            input('description'),
            $account->getNumber()
        );

        $result = $this->accountRepository->save($account, input('customers_id'));

        return response()
            ->httpCode(200)
            ->json([
                'message' => 'Success',
                'data' => []
            ]);
    }

    /**
     * @param int $idAccount
     */
    public function update(int $idAccount): void
    {
        $result = $this->accountRepository->findOneByCustomer($idAccount, input('customers_id'));

        if (!$result) {
            return response()
                ->httpCode(204)
                ->json([
                    'message' => 'Conta não encontrada.',
                    'data' => []
                ]);
        }

        $account = new CustomerAccount();
        $account->fill(
            !empty(input('current_balance')) ? input('current_balance') : $result->getCurrentBalance(),
            !empty(input('type')) ? input('type') : $result->getType(),
            !empty(input('description')) ? input('description') : $result->getDescription(),
            $account->getNumber(),
            $result->getId()
        );

        $result = $this->accountRepository->save($account, input('customers_id'));

        return response()
            ->httpCode(200)
            ->json([
                'message' => 'Success',
                'data' => []
            ]);
    }

    /**
     * @param int $idAccount
     * @param int $idCustomer
     */
    public function delete(int $idAccount, int $idCustomer): void
    {
        $result = $this->accountRepository->findOneByCustomer($idAccount, $idCustomer);

        if (!$result) {
            return response()
                ->httpCode(204)
                ->json([
                    'message' => 'Conta não encontrada.',
                    'data' => []
                ]);
        }

        $result = $this->accountRepository->remove($result);

        if (!$result) {
            return response()
                ->httpCode(400)
                ->json([
                    'message' => 'Erro ao excluir a conta.',
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

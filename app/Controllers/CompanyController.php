<?php

namespace App\Controllers;

use Exception;
use DateTimeImmutable;
use App\Database\Connection;
use App\Models\Customer\CustomerCompany;
use App\Models\CustomerAccount\CustomerAccount;
use App\Repositories\CustomerRepository\CustomerCompanyRepository;
use App\Repositories\CustomerRepository\CustomerRepositoryInterface;
use App\Repositories\CustomerAccountRepository\CustomerAccountRepository;
use function App\Helpers\input;
use function App\Helpers\response;

class CompanyController
{
    private CustomerRepositoryInterface $companyRepository;

    public function __construct()
    {
        $this->companyRepository = new CustomerCompanyRepository(Connection::getInstance());
        $this->accountRepository = new CustomerAccountRepository(Connection::getInstance());
    }

    public function index()
    {
        $result = $this->companyRepository->findAll();

        if (!$result) {
            return response()
                ->httpCode(204)
                ->json([
                    'message' => 'Não há empresas.',
                    'data' => []
                ]);
        }

        $companies = [];

        foreach ($result as $key => $company) {
            $companies = [
                'id' => $company->getId(),
                'company_name' => $company->getCompanyName(),
                'cnpj' => $company->getCnpj(),
                'state_registration' => $company->getStateRegistration(),
                'address' => $company->getAddress(),
                'telephone' => $company->getTelephone(),
                'email' => $company->getEmail(),
                'foundation_date' => $company->getFoundationDate()->format('Y-m-d')
            ];

            $accounts = [];
            foreach ($company->getAccounts() as $account) {
                $accounts[] = [
                    'current_balance' => $account->getCurrentBalance(),
                    'type' => $account->getTypeAccount(),
                    'description' => $account->getDescription(),
                    'number' => $account->getNumber()
                ];
            }

            $companies['accounts'] = $accounts;
        }

        return response()
            ->httpCode(200)
            ->json([
                'message' => 'Success',
                'data' => [$companies]
            ]);
    }

    /**
     * @param int $id
     */
    public function getById(int $id)
    {
        $result = $this->companyRepository->findOne($id);

        if (!$result) {
            return response()
                ->httpCode(204)
                ->json([
                    'message' => 'Empresa não encontrado.',
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
                    'company_name' => $result->getCompanyName(),
                    'cnpj' => $result->getCnpj(),
                    'state_registration' => $result->getStateRegistration(),
                    'address' => $result->getAddress(),
                    'telephone' => $result->getTelephone(),
                    'email' => $result->getEmail(),
                    'foundation_date' => $result->getFoundationDate()->format('Y-m-d'),
                    'accounts' => $accounts
                ]
            ]);
    }

    public function create()
    {
        try {
            $company = new CustomerCompany();
            $company->fill(
                input('address'),
                input('telephone'),
                input('email'),
                input('password'),
                input('company_name'),
                input('cnpj'),
                input('state_registration'),
                new DateTimeImmutable(input('foundation_date'))
            );

            $account = new CustomerAccount();
            $account->fill(
                0.00,
                1,
                '',
                $account->getNumber()
            );

            $result = $this->companyRepository->save($company);
            $result = $this->accountRepository->save($account, $result);

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

    /**
     * @param int $id
     */
    public function update(int $id)
    {
        $result = $this->companyRepository->findOne($id);

        if (!$result) {
            return response()
                ->httpCode(204)
                ->json([
                    'message' => 'Empresa não encontrado.',
                    'data' => []
                ]);
        }

        $company = new CustomerCompany();
        $company->fill(
            !empty(input('address')) ? input('address') : $result->getAddress(),
            !empty(input('telephone')) ? input('telephone') : $result->getTelephone(),
            !empty(input('email')) ? input('email') : $result->getEmail(),
            !empty(input('password')) ? input('password') : $result->getPassword(),
            !empty(input('company_name')) ? input('company_name') : $result->getCompanyName(),
            !empty(input('cnpj')) ? input('cnpj') : $result->getCnpj(),
            !empty(input('state_registration')) ? input('state_registration') : $result->getStateRegistration(),
            !empty(input('foundation_date')) ? new DateTimeImmutable(input('foundation_date')) : $result->getFoundationDate(),
            $result->getId()
        );

        $result = $this->companyRepository->save($company);

        if (!$result) {
            return response()
                ->httpCode(400)
                ->json([
                    'message' => 'Erro ao atualizar a empresa.',
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

    /**
     * @param int $id
     */
    public function delete(int $id)
    {
        $result = $this->companyRepository->findOne($id);

        if (!$result) {
            return response()
                ->httpCode(204)
                ->json([
                    'message' => 'Empresa não encontrada.',
                    'data' => []
                ]);
        }

        $result = $this->companyRepository->remove($result);

        if (!$result) {
            return response()
                ->httpCode(400)
                ->json([
                    'message' => 'Erro ao excluir a empresa.',
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

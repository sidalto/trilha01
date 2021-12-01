<?php

namespace App\Controllers;

use DateTimeImmutable;
use App\Database\Connection;
use App\Models\Customer\CustomerCompany;
use App\Repositories\CustomerRepository\CustomerCompanyRepository;
use App\Repositories\CustomerRepository\CustomerRepositoryInterface;

use function App\Helpers\input;

class CompanyController
{
    private CustomerRepositoryInterface $companyRepository;

    public function __construct()
    {
        $this->companyRepository = new CustomerCompanyRepository(Connection::getInstance());
    }

    public function index()
    {
        var_dump($this->companyRepository->findAll());
    }

    public function getById(int $id)
    {
        var_dump($this->companyRepository->findOne($id));
    }

    public function create()
    {
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

        $this->companyRepository->save($company);
    }

    public function update(int $id)
    {
        $existentCompany = $this->companyRepository->findOne($id);

        if (!$existentCompany) {
            var_dump("Not possible update");
            exit;
        }

        $company = new CustomerCompany();
        $company->fill(
            !empty(input('address')) ? input('address') : $existentCompany->getAddress(),
            !empty(input('telephone')) ? input('telephone') : $existentCompany->getTelephone(),
            !empty(input('email')) ? input('email') : $existentCompany->getEmail(),
            !empty(input('password')) ? input('password') : $existentCompany->getPassword(),
            !empty(input('company_name')) ? input('company_name') : $existentCompany->getCompanyName(),
            !empty(input('cnpj')) ? input('cnpj') : $existentCompany->getCnpj(),
            !empty(input('state_registration')) ? input('state_registration') : $existentCompany->getStateRegistration(),
            !empty(input('foundation_date')) ? new DateTimeImmutable(input('foundation_date')) : $existentCompany->getFoundationDate(),
            $existentCompany->getId()
        );

        var_dump($this->companyRepository->save($company));
    }

    public function delete(int $id)
    {
        $existentCompany = $this->companyRepository->findOne($id);

        if (!$existentCompany) {
            var_dump("Not possible delete");
            exit;
        }

        var_dump($this->companyRepository->remove($existentCompany));
    }
}

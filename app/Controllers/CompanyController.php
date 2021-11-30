<?php

namespace App\Controllers;

use App\Database\Connection;
use DateTimeImmutable;
use App\Models\Customer\CustomerCompany;
use App\Repositories\CustomerRepository\CustomerCompanyRepository;
use App\Repositories\CustomerRepository\CustomerRepositoryInterface;

class CompanyController
{
    private CustomerRepositoryInterface $companyRepository;

    // public function __construct(CustomerRepositoryInterface $companyRepository)
    // {
    //     $this->companyRepository = $companyRepository;
    // }

    public function __construct()
    {
        $this->companyRepository = new CustomerCompanyRepository(Connection::getInstance());
    }

    public function index()
    {
        var_dump($this->companyRepository->findAll());
    }

    public function getById(array $data)
    {
        $id = $data['params'];
        var_dump($this->companyRepository->findOne($id));
    }

    public function create(array $data)
    {
        $company = new CustomerCompany();

        $company->fill(
            $data['address'],
            $data['telephone'],
            $data['email'],
            $data['password'],
            $data['company_name'],
            $data['cnpj'],
            $data['state_registration'],
            new DateTimeImmutable($data['foundation_date'])
        );

        $this->companyRepository->save($company);
    }

    public function update(array $data)
    {
        $id = $data['id'];
        $data = $data['data'];

        $existentCompany = $this->companyRepository->findOne($id);

        if (!$existentCompany) {
            var_dump("Not possible update");
            exit;
        }

        $company = new CustomerCompany();
        $company->fill(
            isset($data['telephone']) ? $data['telephone'] : $existentCompany->getTelephone(),
            isset($data['address']) ? $data['address'] : $existentCompany->getAddress(),
            isset($data['email']) ? $data['email'] : $existentCompany->getEmail(),
            isset($data['password']) ? $data['password'] : $existentCompany->getPassword(),
            isset($data['company_name']) ? $data['company_name'] : $existentCompany->getCompanyName(),
            isset($data['cnpj']) ? $data['cnpj'] : $existentCompany->getCnpj(),
            isset($data['state_registration']) ? $data['state_registration'] : $existentCompany->getStateRegistration(),
            isset($data['foundation_date']) ? new DateTimeImmutable($data['foundation_date']) : $existentCompany->getFoundationDate(),
            $existentCompany->getId()
        );

        var_dump($this->companyRepository->save($company));
    }

    public function delete(array $data)
    {
        $id = $data['params'];
        $existentCompany = $this->companyRepository->findOne($id);

        if (!$existentCompany) {
            var_dump("Not possible delete");
            exit;
        }

        var_dump($this->companyRepository->remove($existentCompany));
    }
}

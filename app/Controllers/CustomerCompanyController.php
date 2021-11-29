<?php

namespace App\Controllers;

use DateTimeImmutable;
use App\Models\Customer\CustomerCompany;
use App\Repositories\CustomerRepository\CustomerRepositoryInterface;

class CustomerCompanyController
{
    private CustomerRepositoryInterface $companyRepository;

    public function __construct(CustomerRepositoryInterface $companyRepository)
    {
        $this->companyRepository = $companyRepository;
    }

    public function index()
    {
        var_dump($this->companyRepository->findAll());
    }

    public function getById(int $id)
    {
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
        $existentCompany = $this->companyRepository->findOne($data['args']);

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

<?php

namespace App\Controllers;

use DateTimeImmutable;
use App\Database\Connection;
use App\Models\CustomerAccount\CustomerAccount;
use App\Repositories\CustomerAccountRepository\CustomerAccountRepository;
use App\Repositories\CustomerAccountRepository\CustomerAccountRepositoryInterface;

class AccountController
{
    private CustomerAccountRepositoryInterface $accountRepository;

    // public function __construct(CustomerRepositoryInterface $companyRepository)
    // {
    //     $this->companyRepository = $companyRepository;
    // }

    public function __construct()
    {
        $this->accountRepository = new CustomerAccountRepository(Connection::getInstance());
    }

    public function index(int $idCustomer)
    {
        var_dump($this->accountRepository->findAllByCustomer($idCustomer));
    }

    public function getById(int $idAccount, int $idCustomer)
    {
        var_dump($this->accountRepository->findOneByCustomer($idAccount, $idCustomer));
    }

    public function create(array $data)
    {
        $account = new CustomerAccount();

        $account->fill(
            $data['address'],
            $data['telephone'],
            $data['email'],
            $data['password'],
            $data['company_name'],
            $data['cnpj'],
            $data['state_registration'],
            new DateTimeImmutable($data['foundation_date'])
        );

        $this->accountRepository->save($account);
    }

    public function update(array $data)
    {
        $id = $data['id'];
        $data = $data['data'];

        $existentCompany = $this->accountRepository->findOne($id);

        if (!$existentCompany) {
            var_dump("Not possible update");
            exit;
        }

        $company = new CustomerAccount();
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

        var_dump($this->accountRepository->save($company));
    }

    public function delete(array $data)
    {
        $id = $data['params'];
        $existentCompany = $this->accountRepository->findOne($id);

        if (!$existentCompany) {
            var_dump("Not possible delete");
            exit;
        }

        var_dump($this->accountRepository->remove($existentCompany));
    }
}

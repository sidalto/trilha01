<?php

namespace App\Controllers;

use App\Models\Customer\CustomerCompany;
use App\Repositories\CustomerRepository\CustomerCompanyRepository;

class CustomerCompanyController
{
    private CustomerCompanyRepository $customerCompanyRepository;
    private CustomerCompany $customerCompany;

    public function __construct(CustomerCompanyRepository $customerCompanyRepository, CustomerCompany $customerCompany)
    {
        $this->customerCompanyRepository = $customerCompanyRepository;
        $this->customerCompany = $customerCompany;
    }

    public function index()
    {
    }

    public function getAll()
    {
        $customerCompanies = $this->customerCompanyRepository->getAll();

        echo json_encode([$customerCompanies]);
    }

    // public function create(Request $request)
    // {
    //     $company = new Company(
    //         $request['company_name'],
    //         $request['cnpj'],
    //         $request['state_registration'],
    //         $request['address'],
    //         $request['telephone'],
    //         $request['foundation_date']
    //     );

    //     $this->companyRepository->add($company);

    //     echo json_encode(['message' => 'success']);
    // }

    public function store($category)
    {
    }

    public function show($data)
    {
    }

    public function edit($data)
    {
    }

    public function save($data)
    {
    }

    public function remove($data)
    {
    }
}

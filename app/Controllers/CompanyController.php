<?php

namespace App\Controllers;

use App\Models\Client\Company;
use App\Repositories\ClientRepository\CompanyRepository;

class CategoryController
{
    private CompanyRepository $companyRepository;
    private Company $company;

    public function __construct(CompanyRepository $companyRepository, Company $company)
    {
        $this->companyRepository = $companyRepository;
        $this->company = $company;
    }

    public function index()
    {
    }

    public function create(Request $request)
    {
        $company = new Company(
            $request['company_name'],
            $request['cnpj'],
            $request['state_registration'],
            $request['address'],
            $request['telephone'],
            $request['foundation_date']
        );

        $this->companyRepository->add($company);

        echo json_encode(['message' => 'success']);
    }

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
